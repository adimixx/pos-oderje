<?php

namespace App\Http\Middleware;

use Closure;

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

        if ($machine === null){
            return redirect()->route('conf');
        }
        return $next($request);
    }
}
