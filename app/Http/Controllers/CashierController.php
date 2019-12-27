<?php

namespace App\Http\Controllers;

use App\collection;
use App\device;
use App\Http\Middleware\CheckMachineConf;
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
use Illuminate\Support\Facades\Cookie;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:pos']);
        $this->middleware(CheckMachineConf::class);
    }

    public function listItem(Request $request)
    {
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');
        $products = ojdb_product_business_merchant::with('product:p_id,p_name as name,p_category_code as category,p_brand as brand,p_price as price,p_image as img');

        if (isset($merchantCookie)) {
            $products = $products->where('m_id', $merchantCookie);
        } else {
            $products = $products->where('b_id', $businessCookie);
        }

        return $products->get(['pbm_id as id', 'p_id']);
    }

    public function index()
    {
        $start_money = Auth::user()->userlog()->where('log_out', 'false')->first()->start_money;
        if ($start_money === null) return view('CashierPanel_StartMoney');

        $priceUnit = "RM";
        $taxSetting = 0.06;
        return view('CashierPanel_Main', compact('priceUnit', 'taxSetting'));
    }

    public function ojBill_create(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric'
        ]);

        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');

        $bill = new ojdb_bill();
        $bill->bill_date = now();
        $bill->bill_reference = "OJ" . str_replace(" ", "", str_replace(":", "", str_replace("-", "", (string)$bill->bill_date)));
        $bill->amount = $validated->amount;
        $bill->save();

        $pruser = (isset($merchantCookie)) ? ojdb_merchant::find($merchantCookie) : ojdb_business::find($businessCookie);
        $pruser = $pruser->PRUSER()->u_id;

        return array('url' => 'https://www.oderje.com/index.php?d=');

    }

    public function recordStartMoney(Request $request)
    {
        $validated = $request->validate(['start' => 'required|numeric']);

        $start = ((integer)$validated['start']);

        $userlog = Auth::user()->userlog()->where('log_out', 'false')->first();
        $userlog->start_money = $start;
        $userlog->save();

        return redirect()->route('cashier');
    }

    public function transactionRecord(Request $request)
    {
        $taxSetting = 0.06;
        $order = $request->order;
        $moneyin = ((double)$request->moneyin) * 100;
        $customer_order_data = array();
        $total = 0;

        $bill = new ojdb_bill();
        $bill->bill_date = now();
        $bill->bill_reference = "OJ" . str_replace(" ", "", str_replace(":", "", str_replace("-", "", (string)$bill->bill_date)));
        $bill->save();

        $data['bill_id'] = $bill->bill_id;
        $data['order_date'] = now();
        $data['order_received_date'] = now();
        $data['order_status'] = 'PAID';

        foreach ($order as $ord) {
            $data['quantity'] = $ord['quantity'];
            if (isset($ord['item']['id'])) {
                $pbm = ojdb_product_business_merchant::find($ord['item']['id']);
                $pbm->quantity = $pbm->quantity - $ord['quantity'];
                $price = $pbm->product->p_price * 100;
                $pbm->save();

                $data['pbm_id'] = $ord['item']['id'];
                $data['order_type'] = 'product-counter';
            } else {
                $price = $ord['item']['product']['price'] * 100;
                $data['order_type'] = 'custom-counter';
                $data['manual_price'] = $price;
            }
            $customer_order_data[] = $data;
            $total += ($price * $ord['quantity']);
        }
        $tax = ($total * $taxSetting);

        $transaction = new ojdb_transaction();
        $transaction->type = "CASH";
        $transaction->amount = $total;
        $transaction->tax = $tax;
        $transaction->status = "success";
        $transaction->bill_id = $bill->bill_id;
        $transaction->save();

        ojdb_customer_order::insert($customer_order_data);

        $collection = new collection();
        $collection->bill_id = $bill->bill_id;
        $collection->money_in = $moneyin;
        $collection->device_id = device::where('uuid', Cookie::get('uuid'))->first()->id;
        $collection->user_id = \auth()->user()->getAuthIdentifier();
        $collection->save();

        return array("status" => "ok");
    }
}
