<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->header("Access-Control-Allow-Origin", "*");
        $request->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE");
        $request->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, authorization");

        return $next($request);
    }
}
