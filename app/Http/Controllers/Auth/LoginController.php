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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

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
        $credentials = null;
        $user = User::where('username', $username)->first();
        if (isset($user->ojdb_pruser)) {
            $user_group = $user->pruser()->first()->flc_user_group->first()->GROUP_ID;
            if ($user_group === 2 || $user_group === 3) {
                $credentials = [
                    'username' => $username,
                    'password' => $password
                ];
            }
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

        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $credentials = $this->loginBusinessMerchant($request->username, $request->password, $businessCookie, $merchantCookie);

        if (empty($credentials)) {
            $credentials = $this->loginInitMachine($request->username, $request->password);
        }

        if (empty($credentials)) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }
        if (Auth::attempt($credentials)) {
            //get Machine type cache
            $machine = $request->cookie('machine');
            $uuid = $request->cookie('uuid');

            $device = device::where('uuid', $uuid)->first();

            if ($machine == 1 && isset($uuid)) {
                $lastLogin = user_log::where('user_id', \Illuminate\Support\Facades\Auth::user()->getAuthIdentifier())->orderBy('id','desc')->first();
                if ($lastLogin !== null) $lastLogin->update(['log_out' => true]);

                user_log::Create([
                    'user_id' => Auth::user()->getAuthIdentifier(),
                    'device_id' => $device->id,
                    'log_out' => false
                ]);

                return redirect()->route('cashier');
            } else {
                return redirect()->route('conf');
            }
        } else {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }
    }

    public function logout(Request $request)
    {
        $lastLogin = user_log::where('user_id', \Illuminate\Support\Facades\Auth::user()->getAuthIdentifier())->orderBy('id','desc')->first();
        if ($lastLogin !== null) $lastLogin->update(['log_out' => true]);

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
