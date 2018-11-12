<?php

namespace BBDO\Cms\Http\Middleware\Admin;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class BasicMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Sentinel::check()) {
            return $next($request);
        }
        return redirect()->route('login');
    }
}
