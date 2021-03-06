<?php

namespace BBDOCms\Http\Middleware\Admin;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class AdminMiddleware
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
        if (Sentinel::getUser()->roles()->first() && Sentinel::getUser()->roles()->first()->slug == 'admin') {
            return $next($request);
        } else {
            return redirect()->route('login');
        }
    }
}
