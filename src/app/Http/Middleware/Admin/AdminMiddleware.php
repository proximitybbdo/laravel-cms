<?php

namespace BBDO\Cms\Http\Middleware\Admin;

use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AdminMiddleware
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
        if ( Sentinel::getUser()->roles()->first() ) {
            if ( Sentinel::getUser()->roles()->first()->slug == 'admin' ) {
                return $next($request);
            }
        }
        else return redirect()->route('login');
    }
}
