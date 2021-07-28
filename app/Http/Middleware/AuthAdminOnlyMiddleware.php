<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdminOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authrole = Auth::user() != null ? Auth::user()->role : null;

        if ($authrole == 'guest' || $authrole == null) {
            return abort(404);
        } else {
            return $next($request);
        }
    }
}
