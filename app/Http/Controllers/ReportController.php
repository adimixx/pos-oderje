<?php

namespace App\Http\Controllers;

use App\collection;
use App\User;
use App\user_log;
use Illuminate\Http\Request;
use \Illuminate\Foundation\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getReport()
    {
        $user = User::find(\auth()->user()->getAuthIdentifier());
        $onlineTime = $user->userlog()->where('log_out', false)->first();
        $collection = $user->collection()->where('created_at', '>=', $onlineTime->created_at)->with('bill.transaction')->get();
        $total = 0;
        foreach ($collection as $col) {
            $total += $col->bill->transaction->amount;
        }

        $total = $total / 100;
        return view('Report', compact('user','total', 'collection', 'onlineTime'));

    }

}
