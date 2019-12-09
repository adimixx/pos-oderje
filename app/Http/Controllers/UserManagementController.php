<?php

namespace App\Http\Controllers;

use App\ojdb_business;
use App\ojdb_merchant;
use App\User;
use App\v2tpdev_pruser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function list(Request $request)
    {
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
                $data = ojdb_business::find($validatedRequest->type_id)->User()->with('roles')->get();
            }
            else {
                $data = ojdb_merchant::find($validatedRequest->type_id)->User()->with('roles')->get();
            }
        }

        if (count($error) !== 0) return response()->json(['message' => 'The given data was invalid', 'errors' => $error], 422);
        else return response()->json(['status' => 'ok', 'data' => $data]);
    }

    public function register(Request $request)
    {
        /* id = PRUSER UserID
         * role = 1=Admin, 2=Cashier
         * type = 1=Business, 2=Merchant
         * */

        $validatedRequest = (object)$request->validate([
            'id' => 'required|numeric',
            'username' => '',
            'password' => '',
            'role' => 'required|numeric',
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
        }
        elseif(isset($validatedRequest->username) && !is_null(User::where('username',$validatedRequest->username)->first())){
            $error['username'] = ['Username Already Exists'];
        }

        else {
            //Create new user
            if (!isset($validatedRequest->username)) {
                $generatedUsername = "user".$pruser->u_id.strtotime(Carbon::now());
                $insertData['username'] = $generatedUsername;
            } else $insertData['username'] = $validatedRequest->username;
            $insertData['password'] = (!isset($validatedRequest->password)) ? Hash::make($insertData['username']) : Hash::make($validatedRequest->password);
            $insertData['created_by'] = $pruser->u_id;
            $insertData['name'] = $insertData['username'];

            if ($validatedRequest->type == 1) $insertData['business_id'] = $validatedRequest->type_id;
            else $insertData['merchant_id'] = $validatedRequest->type_id;

            $data = User::Create($insertData);

            if ($validatedRequest->role == 2) $role = Role::findByName('Cashier');
            else if ($validatedRequest->type == 1) $role = Role::findByName('Business Admin');
            else $role = Role::findByName('Merchant Admin');

            $data->assignRole($role);
        }

        if (count($error) !== 0) return response()->json(['message' => 'The given data was invalid', 'errors' => $error], 422);
        else return response()->json(['status' => 'ok', 'data' => $data]);
    }
}
