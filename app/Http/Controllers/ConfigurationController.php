<?php

namespace App\Http\Controllers;

use App\device;
use App\Http\Requests\ConfigurationRequest;
use App\machine_type;
use App\ojdb_business;
use App\ojdb_merchant;
use App\User;
use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades;

class ConfigurationController extends Controller
{
    public function Index(Request $request)
    {
        $machine = $request->cookie('machine');
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $currentSetting = null;

        $machineType = machine_type::all();

        $ojdb_pruser = User::find(Auth::user()->getAuthIdentifier());

        $business = $ojdb_pruser->business()->first();
        $merchant = ojdb_merchant::where('b_id', $business->b_id)->get();

        if ($machine !== null) {
            $currentSetting = array(
                'business' => $business->find($businessCookie),
                'machine' => $machineType[$machine-1]
            );

            if (count($merchant) === 0) array_push($currentSetting, array('merchant' => $merchant->find($merchantCookie)));
        }
        return view('configuration', compact('machineType', 'business', 'merchant', 'currentSetting'));
    }

    public function IndexPOST(Request $data)
    {
        $lifetime = time() + 60 * 60 * 24 * 365; // one year
        $uuidCookie = Facades\Cookie::get('uuid');
        $ip = request()->ip();

        $ojdb_pruser = User::find(Auth::user()->getAuthIdentifier())->pruser()->first();

        $business = $ojdb_pruser->business()->first()->b_id;
        $merchant = ojdb_merchant::where('b_id', $business)->get();
        if (count($merchant) !== 0) {
            if (!isset($data['merchant'])) {
                return view('configuration');
            }

            $merchant->find($data['merchant']);
            if ($merchant === null) {
                return view('configuration');
            }
        }

        $device = device::where('uuid', $uuidCookie)->orWhere('ip_address', $ip)->first();

        if ($device === null || $uuidCookie === null || $ip === null) {
            $device = new device();
            $uuid = (string)Uuid::generate();
            Facades\Cookie::queue('uuid', $uuid, $lifetime);
            $device->uuid = $uuid;
            $device->ip_address = $ip;
        }

        Facades\Cookie::queue('business', $business, $lifetime);
        $device->business_id = $business;

        Facades\Cookie::queue('machine', $data->machine, $lifetime);
        $device->machine_type = $data['machine'];

        if (isset($data->merchant)) {
            Facades\Cookie::queue('merchant', $data->merchant, $lifetime);
            $device->merchant_id = $data->merchant;
        }

        $device->name = "test";
        $device->status = "OK";

        $device->save();

        return redirect()->route('conf');
    }
}
