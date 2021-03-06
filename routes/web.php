<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('showLoginForm');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/cashier', 'CashierController@index')->name('cashier');
Route::post('/cashier', 'CashierController@recordStartMoney')->name('startMoney');
Route::get('/cashier/list', 'CashierController@listItem')->name('cashierList');
Route::post('/cashier/save', 'CashierController@transactionRecord')->name('transactionRecord');

Route::get('/receipt/print', 'ReceiptController@printReceipt')->name('printReceipt');

Route::get('/configuration', 'ConfigurationController@index')->name('conf');
Route::post('/configuration', 'ConfigurationController@indexPOST')->name('confPOST');

Route::get('/report', 'ReportController@getReport')->name('report');


Route::get('/', function () {
    return redirect('home');
});
