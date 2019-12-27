<?php

namespace App\Http\Middleware;

use App\ojdb_business;
use App\ojdb_merchant;
use Closure;
use Illuminate\Support\Facades\Cookie;

class CheckMachineConf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $machine = $request->cookie('machine');
        $merchantCookie = $request->cookie('merchant');
        $businessCookie = $request->cookie('business');

        if (!isset($machine) || (!isset($merchantCookie) && !isset($businessCookie))){
            return redirect()->route('conf');
        }

        $owner = (isset($merchantCookie)) ? ojdb_merchant::find($merchantCookie) : ojdb_business::find($businessCookie);

        if (!isset($owner)){
            Cookie::queue(Cookie::forget('machine'));
            Cookie::queue(Cookie::forget('merchant'));
            Cookie::queue(Cookie::forget('business'));
            return redirect()->route('logout');
        }

        return $next($request);
    }
}
