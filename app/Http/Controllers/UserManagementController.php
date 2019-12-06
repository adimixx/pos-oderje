<?php

namespace App\Http\Controllers;

use App\ojdb_business;
use App\ojdb_merchant;
use App\User;
use App\v2tpdev_pruser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function listUser(Request $request)
    {
        return csrf_token();
        $userList = null;
        $error = null;

        $pruser = v2tpdev_pruser::find($request->id);
        if (!isset($pruser)) $error = "Invalid user credentials";
        else {
            $userGroup = $pruser->flc_user_group->find(2);
            if (!isset($userGroup)) $userGroup = $pruser->flc_user_group->find(3);
            if (!isset($userGroup)) $error = "Unauthorised Access";

            if (isset($pruser->m_id)) {
                $userList = ojdb_merchant::find($pruser->m_id);
            } elseif (isset($pruser->b_id) && isset($request->merchant_id)) {
                $userList = ojdb_business::find($pruser->b_id)->Merchant()->find($request->merchant_id);
            } else {
                if (!isset($request->merchant_id)) {
                    $error = "Merchant id not supplied";
                } else {
                    $error = "Database error. Please contact admin";
                }
            }
        }
        return (isset($error)) ? ["error" => $error] : ["data" => $userList->User()->get(['id', 'name', 'username', 'status', 'created_at', 'created_by'])];
    }

    public function adminRegister(Request $request)
    {
        $data = null;
        $error = null;

        $pruser = v2tpdev_pruser::find($request->id);
        if (!isset($pruser)) $error = "User does not exist";
        else {
            $UserGroup = $pruser->flc_user_group->first()->GROUP_ID;
            if (!isset($pruser)) $error = "Invalid user credentials";
            else if (($UserGroup != 2 && $UserGroup != 3) || (!isset($pruser->b_id) && !isset($pruser->m_id))) $error = "Unauthorised Access";
            else {
                if ($UserGroup === 2) {
                    $data = User::Create([
                        'username' => $pruser->u_username,
                        'password' => Hash::make($request->password),
                        'name' => $pruser->u_fullname,
                        'business_id' => $pruser->b_id,
                        'created_by' => $pruser->u_id,
                        'ojdb_PRUSER' => $pruser->u_id,
                    ]);


                } elseif ($UserGroup === 3) {
                    $data = User::Create([
                        'username' => $pruser->u_username,
                        'password' => Hash::make($pruser->u_password),
                        'name' => $pruser->u_fullname,
                        'merchant_id' => $pruser->m_id,
                        'created_by' => $pruser->u_id,
                        'ojdb_PRUSER' => $pruser->u_id,
                    ]);
                }
            }
        }

        if (isset($error)) return ["error" => $error];
        else return ["data" => $data];
    }

    public function userRegister(Request $request){
        $data = null;
        $error = null;

        $pruser = v2tpdev_pruser::find($request->admin_id);
        if (!isset($pruser)) $error = "User does not exist";
        else {
            $data = User::Create([
                'username' => $request->username,
                'password' => (isset($request->password)) ? Hash::make($request->password) : Hash::make('1234'),
                'name' => (isset($request->name)) ? $request->name : $request->username,
                'business_id' => (!isset($request->m_id) && isset($pruser->b_id)) ?$pruser->b_id : null,
                'merchant_id' => (isset($request->m_id))? $request->m_id : $pruser->m_id,
                'created_by' => $pruser->u_id
            ]);
        }

        if (isset($error)) return ["error" => $error];
        else return ["data" => $data];
    }
}
