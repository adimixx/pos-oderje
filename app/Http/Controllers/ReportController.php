<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getReport()
    {
        $user = Auth::user();
        $onlineTime = $user->userlog()->where('log_out', false)->first();
        $collection = $user->collection()->where('created_at', '>=', $onlineTime->created_at)->with('bill.transaction')->get();
        $total = 0;
        foreach ($collection as $col) {
            $total += ($col->bill->transaction->amount + $col->bill->transaction->tax);
        }
        $totalCash = ($total + $onlineTime->start_money) /100;
        $total = $total / 100;
        return view('Report', compact('user','total', 'collection','totalCash', 'onlineTime'));

    }

}
