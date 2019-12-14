<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('manage_user/list', 'UserManagementController@list')->name('userList');
Route::post('/manage_user/add', 'UserManagementController@register')->name('userRegister');

Route::get('manage_machine/list', 'MachineManagementController@list')->name('machineList');


