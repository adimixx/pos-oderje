<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckMachineConf;
use App\ojdb_bill;
use App\ojdb_business;
use App\ojdb_merchant;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /*
    public function __construct()
    {
        $this->middleware(['permission:pos']);
        $this->middleware(CheckMachineConf::class);
    }
*/
    public function printReceipt(Request $request){
        $validatedRequest = (object)$request->validate([
            'id' => 'required|numeric'
        ]);

        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $BusinessMerchant = (isset($merchantCookie)) ? ojdb_merchant::find($merchantCookie) : ojdb_business::find($businessCookie);
        $bill = ojdb_bill::find($validatedRequest->id);

        return $BusinessMerchant;
        //return view('ReceiptPrint', compact('priceUnit', 'taxSetting'));

    }
}
