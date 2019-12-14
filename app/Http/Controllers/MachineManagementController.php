<?php

namespace App\Http\Controllers;

use App\device;
use App\ojdb_business;
use App\ojdb_merchant;
use App\v2tpdev_pruser;
use Illuminate\Http\Request;

class MachineManagementController extends Controller
{
    public function list(Request $request){
        /* id = PRUSER UserID
         * type = 1=Business, 2=Merchant
         * */

        $validatedRequest = (object)$request->validate([
            'id' => 'required|numeric',
            'type' => 'required|numeric',
            'type_id' => 'required|numeric'
        ]);

        $data = [];
        $error = [];

        $pruser = v2tpdev_pruser::find($validatedRequest->id);
        if (!isset($pruser)) {
            $error['id'] = ['User does not exist'];
        } elseif (!isset($pruser->flc_user_group->first()->GROUP_ID)) {
            $error['id'] = ['Invalid user credentials'];
        } elseif ($validatedRequest->type === 1 && is_null($pruser->Business()->find($validatedRequest->type_id))) {
            $error['type_id'] = ['Invalid business id'];
        } elseif ($validatedRequest->type === 2 && is_null($pruser->Merchant()->find($validatedRequest->type_id))) {
            $error['type_id'] = ['Invalid merchant id'];
        } else {
            if($validatedRequest->type == 1){
                $data = device::with(['user_log','collection'])->where('business_id',$validatedRequest->type_id)->get();
            }
            else {
                $data = device::with(['user_log','collection'])->where('merchant_id',$validatedRequest->type_id)->get();
            }
        }

        if (count($error) !== 0) return response()->json(['message' => 'The given data was invalid', 'errors' => $error], 422);
        else return response()->json(['status' => 'ok', 'data' => $data]);
    }
}
