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
    public function Index(Request $request)
    {
        $machineType = machine_type::all();
        if (in_array("Business Admin", Auth::user()->getRoleNames()->toArray())) {
            $business = Auth::user()->business()->first();
            $merchant = $business->merchant()->get();
        }

        else if(in_array("Merchant Admin", Auth::user()->getRoleNames()->toArray())) {
            $merchant = Auth::user()->merchant()->first();
            $business = $merchant->Business()->first();
        }

        $machine = $request->cookie('machine');
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $currentSetting = null;

        if ($machine !== null) {
            $currentSetting = array(
                'business' => $business->find($businessCookie),
                'machine' => $machineType[$machine - 1]
            );

            if (count($merchant) !== 0) $currentSetting['merchant'] = $merchant->find($merchantCookie);
        }
        return view('conf', compact('machineType', 'business', 'merchant', 'currentSetting'));
    }

    public function IndexPOST(Request $request)
    {
        $uuid = $request->cookie('uuid');
        $device = device::where('uuid', $uuid)->first();
        $lifetime = time() + 60 * 60 * 24 * 365;

        $business = Auth::user()->business()->first();
        if (!isset($business)) {
            $merchant = Auth::user()->merchant()->first();
            $business = $merchant->Business()->first();
        } else {
            $merchant = $business->merchant()->get();

            if (count($merchant) !== 0) {
                if (!isset($request->merchant)) {
                    return redirect()->route('conf');
                }

                $merchant = $merchant->find($request->merchant);
                if ($merchant === null) {
                    return redirect()->route('conf');
                }
            }
        }

        Cookie::queue('business', $business->b_id, $lifetime);
        $device->business_id = $business->b_id;

        if (isset($request->merchant)) {
            Cookie::queue('merchant', $merchant->m_id, $lifetime);
            $device->merchant_id = $merchant->m_id;
        }

        Cookie::queue('machine', $request->machine, $lifetime);
        $device->machine_type = $request->machine;

        $device->name = "test";
        $device->status = "OK";
        $device->save();

        return redirect()->route('conf');
    }
}
