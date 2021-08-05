<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StripEmptyParamMiddleware
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
        $query      = request()->query();
        $querycount = count($query);

        foreach ($query as $key => $value) {
            if ($value == '') {
                unset($query[$key]);
            }
        }

        $path = url()->current() . (!empty($query) ? '/?' . http_build_query($query) : '');
        if ($querycount > count($query)) {
            return redirect()->to($path);
        }

        return $next($request);
    }
}
