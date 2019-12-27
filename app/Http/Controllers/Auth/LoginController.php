<?php

namespace App\Http\Controllers\Auth;

use App\device;
use App\Http\Controllers\Controller;
use App\ojdb_business;
use App\ojdb_merchant;
use App\User;
use App\user_log;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Webpatser\Uuid\Uuid;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        $connector = new FilePrintConnector("php://stdout");
        $printer = new Printer($connector);
        $printer -> text("Hello World!\n");
        $printer -> cut();
        $printer -> close();


        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $machineOwner = null;

        if (isset($merchantCookie)) {
            $machineDetails = ojdb_merchant::find($merchantCookie, ['m_id', 'm_name as name']);
            $machineOwner = "Merchant";
        } elseif (isset($businessCookie)) {
            $machineDetails = ojdb_business::find($businessCookie, ['b_id', 'b_name as name']);
            $machineOwner = "Business";
        } else {
            $machineDetails = null;
        }
        return view('auth.login', compact(['machineDetails', 'machineOwner']));
    }

    public function loginBusinessMerchant($request)
    {
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $credentials = null;
        if (isset($businessCookie)) {
            $credentials = [
                'username' => $request->username,
                'password' => $request->password,
                'business_id' => $businessCookie
            ];
        } else if (isset($merchantCookie)) {
            $credentials = [
                'username' => $request->username,
                'password' => $request->password,
                'merchant_id' => $merchantCookie
            ];
        }

        return $credentials;
    }

    public function loginInitMachine($request)
    {
        $credentials = null;
        $username = $request->username;
        if (count(User::role('Business Admin')->where('username', $username)->get()) != 0 || count(User::role('Merchant Admin')->where('username', $username)->get()) != 0|| count(User::role('Super Admin')->where('username', $username)->get()) != 0) {
            $credentials = [
                'username' => $username,
                'password' => $request->password,
            ];
        }
        return $credentials;
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->loginBusinessMerchant($request);
        $credentials = (empty($credentials)) ? $this->loginInitMachine($request) : $credentials;
        if (empty($credentials)) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }

        if (Auth::attempt($credentials)) {
            $uuid = $request->cookie('uuid');

            if (isset($uuid)) {
                $device = device::where('uuid', $uuid)->first();
            }

            if (!isset($uuid) || $device === null) {
                $lifetime = time() + 60 * 60 * 24 * 365; // one year
                $device = new device();
                $uuid = (string)Uuid::generate();
                Cookie::queue('uuid', $uuid, $lifetime);

                $device->uuid = $uuid;
                $device->status = "INIT";
                $device->save();
            }

            $lastLogin = user_log::where('user_id', Auth::user()->getAuthIdentifier())->orderBy('id', 'desc')->where('log_out', false)->first();
            if ($lastLogin !== null) $lastLogin->update(['log_out' => true]);
            user_log::Create([
                'user_id' => Auth::user()->getAuthIdentifier(),
                'device_id' => $device->id,
                'log_out' => false
            ]);

            $role = Auth::user()->getRoleNames()->toArray();
            if (array_search("Merchant Admin", $role) == 0 || array_search("Business Admin", $role) == 0) {
                if ($device->machine_type == 1) {
                    return redirect()->route('cashier');
                } elseif (is_null($device->machine_type)) {
                    return redirect()->route('conf');
                }
                return redirect()->route('home');
            } else if (array_search("Super Admin", $role)) {
                return redirect()->route('conf');
            } else if (array_search("Cashier", $role)) {
                return redirect()->route('home');
            }
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function logout (Request $request){
        $user = Auth::user();
        $userlog = $user->userlog()->where('log_out', false)->first();

        if (!is_null($userlog->start_money)){
            $collection = $user->collection()->where('created_at', '>=', $userlog->created_at)->with('bill.transaction')->get();
            $total = 0;

            foreach ($collection as $col) {
                $total += ($col->bill->transaction->amount + $col->bill->transaction->tax);
            }

            $totalCash = $total + $userlog->start_money;
            if (is_null($userlog->end_money) && !isset($request->start)){
                $totalCash = $totalCash/100;
                $total = $total/100;

                return view('CashierPanel_EndMoney', compact('total', 'totalCash'));
            }

            else{
                $validated = $request->validate(['start'=>'required|numeric']);
                $end = ((integer)$validated['start']);

                $userlog->end_money = $end;
                $userlog->calculated_end_money = $totalCash;
                $userlog->difference = $end - $totalCash;
                $userlog->log_out = true;
                $userlog->save();
            }
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/');
    }


}
