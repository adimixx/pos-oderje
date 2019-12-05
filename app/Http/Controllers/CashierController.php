<?php

namespace App\Http\Controllers;

use App\collection;
use App\device;
use App\ojdb_bill;
use App\ojdb_business;
use App\ojdb_customer_order;
use App\ojdb_merchant;
use App\ojdb_product;
use App\ojdb_product_business_merchant;
use App\ojdb_transaction;
use App\users_merchant;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Array_;

class CashierController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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

        $priceUnit = "RM";
        $taxSetting = 0.06;
        return view('CashierPanel_Main', compact('priceUnit', 'taxSetting'));
    }

    public function transactionRecord(Request $request)
    {
        $taxSetting = 1.06;
        $order = $request->order;
        $data = array();
        $total = 0;

        $bill = new ojdb_bill();

        foreach ($order as $ord) {
            if (isset($ord['item']['id'])) {
                $product = ojdb_product_business_merchant::find($ord['item']['id'])->product;
                $data[] = [
                    'pbm_id' => $ord['item']['id'],
                    'quantity' => $ord['quantity'],
                    'bill_id' => $bill->bill_id
                ];
            }
            $total += ($product->p_price * $ord['quantity']);
        }

        $total = $total * $taxSetting;

        $transaction = new ojdb_transaction();
        $transaction->type = "CASH";
        $transaction->amount = $total * 100;
        $transaction->status = "success";
        $transaction->bill_id = $bill->bill_id;
        $transaction->save();

        ojdb_customer_order::insert($data);

        $collection = new collection();
        $collection->bill_id = $bill->bill_id;
        $collection->save();

        return array("status" => "ok");
    }

}
