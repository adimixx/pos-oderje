<?php

namespace App\Http\Controllers\Auth;

use App\device;
use App\Http\Controllers\Controller;
use App\ojdb_business;
use App\ojdb_merchant;
use App\User;
use App\user_log;
use App\v2tpdev_flc_user_group_mapping;
use App\v2tpdev_pruser;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Element;
use Webpatser\Uuid\Uuid;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $machineOwner = null;

        if (isset($merchantCookie)) {
            $machineDetails = ojdb_merchant::find($merchantCookie)->first(['m_id', 'm_name as name']);
            $machineOwner = "Merchant";
        } elseif (isset($businessCookie)) {
            $machineDetails = ojdb_business::find($businessCookie)->first(['b_id', 'b_name as name']);
            $machineOwner = "Business";
        } else {
            $machineDetails = null;
        }
        return view('auth.login', compact(['machineDetails', 'machineOwner']));
    }

    public function loginBusinessMerchant($username, $password, $businessCookie, $merchantCookie)
    {
        $credentials = null;
        if (isset($businessCookie)) {
            $credentials = [
                'username' => $username,
                'password' => $password,
                'business_id' => $businessCookie
            ];
        } else if (isset($merchantCookie)) {
            $credentials = [
                'username' => $username,
                'password' => $password,
                'merchant_id' => $merchantCookie
            ];
        }

        return $credentials;
    }

    public function loginInitMachine($username, $password)
    {
        $credentials = [
            'username' => $username,
            'password' => $password
        ];
        return $credentials;
    }


    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');

        if (isset($merchantCookie) || isset($businessCookie)) {
            $credentials = $this->loginBusinessMerchant($request->username, $request->password, $businessCookie, $merchantCookie);

            if (empty($credentials)) {
                $this->incrementLoginAttempts($request);
                return $this->sendFailedLoginResponse($request);
            }

        } else {
            $credentials = $this->loginInitMachine($request->username, $request->password);
        }

        if ($credentials !== null && Auth::attempt($credentials)) {
            $uuid = $request->cookie('uuid');
            $ip = request()->ip();
            $role = Auth::user()->getRoleNames()->toArray();

            if (!isset($uuid)) {
                $lifetime = time() + 60 * 60 * 24 * 365; // one year

                $device = new device();
                $uuid = (string)Uuid::generate();
                Cookie::queue('uuid', $uuid, $lifetime);

                $device->uuid = $uuid;
                $device->ip_address = $ip;
                $device->status = "INIT";
                $device->save();
            } else {
                $device = device::where('uuid', $uuid)->orWhere('ip_address', $ip)->first();
            }

            $lastLogin = user_log::where('user_id', Auth::user()->getAuthIdentifier())->orderBy('id', 'desc')->where('log_out', false)->first();
            if ($lastLogin !== null) $lastLogin->update(['log_out' => true]);
            user_log::Create([
                'user_id' => Auth::user()->getAuthIdentifier(),
                'device_id' => $device->id,
                'log_out' => false
            ]);

            if (array_search("Merchant Admin", $role) || array_search("Business Admin", $role)) {
                if ($device->machine_type == 1) {
                    return redirect()->route('cashier');
                }
            } else if (array_search("Super Admin", $role)) {
                return redirect()->route('conf');
            } else if (array_search("Cashier", $role)) {
                return redirect()->route('home');
            }
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);

    }

    public function logout(Request $request)
    {
        $lastLogin = user_log::where('user_id', \Illuminate\Support\Facades\Auth::user()->getAuthIdentifier())->orderBy('id', 'desc')->first();
        if ($lastLogin !== null) $lastLogin->update(['log_out' => true]);

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
