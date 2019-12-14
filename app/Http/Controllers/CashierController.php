<?php

namespace App\Http\Controllers;

use App\collection;
use App\ojdb_bill;
use App\ojdb_business;
use App\ojdb_customer_order;
use App\ojdb_merchant;
use App\ojdb_product_business_merchant;
use App\ojdb_transaction;
use App\users_merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:pos']);
    }

    public function listItem(Request $request)
    {
        $machine = $request->cookie('machine');
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $products = ojdb_product_business_merchant::with('product:p_id,p_name as name,p_category_code as category,p_brand as brand,p_price as price,p_image as img');

        if (!empty($merchant_id)) {
            $merchant_id = ojdb_merchant::find($merchantCookie)->m_id;

            if ($merchant_id == null) {
                return 'fail';
            }

            $products = $products->where('m_id', $merchant_id);
        } else {
            $business_id = ojdb_business::find($businessCookie)->b_id;

            if ($business_id == null) {
                return 'fail';
            }

            $products = $products->where('b_id', $business_id);
        }

        return $products->get(['pbm_id as id', 'p_id']);
    }

    public function index()
    {
        $machine = Cache::get('machine');
        $merchantCache = Cache::get('merchant');
        $businessCache = Cache::get('business');
        $start_money = Auth::user()->userlog()->where('log_out','false')->first()->start_money;
        if ($start_money === null) return view('CashierPanel_StartMoney');

        $priceUnit = "RM";
        $taxSetting = 0.06;
        return view('CashierPanel_Main', compact('priceUnit', 'taxSetting'));
    }

    public function recordStartMoney (Request $request){
        $validated = $request->validate(['start'=>'required|numeric']);

        $start = ((integer)$validated['start']);

        $userlog = Auth::user()->userlog()->where('log_out','false')->first();
        $userlog->start_money = $start;
        $userlog->save();

        return redirect()->route('cashier');
    }

    public function transactionRecord(Request $request)
    {
        $taxSetting = 0.06;
        $order = $request->order;
        $moneyin = ((double)$request->moneyin) * 100;
        $data = array();
        $total = 0;

        $bill = new ojdb_bill();
        $bill->device_id = device::where('uuid',Cookie::get('uuid'))->first()->id;
        $bill->user_id = \auth()->user()->getAuthIdentifier();

        foreach ($order as $ord) {
            if (isset($ord['item']['id'])) {
                $pbm = ojdb_product_business_merchant::find($ord['item']['id']);
                $pbm->quantity = $pbm->quantity - $ord['quantity'];
                $price = $pbm->product->p_price*100;
                $data[] = [
                    'pbm_id' => $ord['item']['id'],
                    'quantity' => $ord['quantity'],
                    'bill_id' => $bill->bill_id,
                    'order_type' => 'product-counter',
                    'order_date' => now(),
                    'manual_price' => null
                ];
                $pbm->save();
            }

            else{
                $price = $ord['item']['product']['price']*100;
                $data[] = [
                    'pbm_id' => null,
                    'quantity' => $ord['quantity'],
                    'bill_id' => $bill->bill_id,
                    'order_type' => 'custom-counter',
                    'order_date' => now(),
                    'manual_price' => $price
                ];
            }

            $total += ($price * $ord['quantity']);
        }

        $tax = ($total * $taxSetting);
        $total = $total;

        $transaction = new ojdb_transaction();
        $transaction->type = "CASH";
        $transaction->amount = $total;
        $transaction->tax = $tax;
        $transaction->status = "success";
        $transaction->bill_id = $bill->bill_id;
        $transaction->save();

        ojdb_customer_order::insert($data);

        $collection = new collection();
        $collection->bill_id = $bill->bill_id;
        $collection->money_in = $moneyin;
        $collection->save();

        return array("status" => "ok");
    }
}
