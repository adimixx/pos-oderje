<?php

namespace App\Http\Controllers;

use App\device;
use App\Http\Requests\ConfigurationRequest;
use App\machine_type;
use App\user_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['permission:conf']);
    }

    public function Index(Request $request)
    {
        $machineType = machine_type::all();
        if (in_array("Business Admin", Auth::user()->getRoleNames()->toArray())) {
            $business = Auth::user()->business()->first();
            $merchant = null;
        } else if (in_array("Merchant Admin", Auth::user()->getRoleNames()->toArray())) {
            $merchant = Auth::user()->merchant()->first();
            $business = null;
        }

        $machine = $request->cookie('machine');
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $currentSetting = null;

        if ($machine !== null) {
            $currentSetting = array(
                'machine' => $machineType[$machine - 1]
            );

            if (isset($business)) $currentSetting['business'] = $business->find($businessCookie);
            if (isset($merchant)) $currentSetting['merchant'] = $merchant->find($merchantCookie);
        }
        return view('Configuration', compact('machineType', 'business', 'merchant', 'currentSetting'));
    }

    public function IndexPOST(Request $request)
    {
        $uuid = $request->cookie('uuid');
        $device = device::where('uuid', $uuid)->first();
        $lifetime = time() + 60 * 60 * 24 * 365;

        $business = Auth::user()->business()->first();
        if (isset($business)) {
            Cookie::queue('business', $business->b_id, $lifetime);
            $device->business_id = $business->b_id;
        } else {
            $merchant = Auth::user()->merchant()->first();
            if (!isset($request->merchant)) {
                return redirect()->route('conf');
            }
            Cookie::queue('merchant', $merchant->m_id, $lifetime);
            $device->merchant_id = $merchant->m_id;
        }

        Cookie::queue('machine', $request->machine, $lifetime);
        $device->machine_type = $request->machine;
        $device->name = 'POS ' . (isset($business)) ? (device::where('business_id', $business->b_id)->count() + 1) . '-' . $business->b_name : (device::where('merchant_id', $merchant->m_id)->count() + 1) . '-' . $merchant->m_name;
        $device->status = "OK";
        $device->save();

        return redirect()->route('conf');
    }
}
