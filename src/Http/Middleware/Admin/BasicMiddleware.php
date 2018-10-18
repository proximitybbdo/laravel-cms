<?php

namespace BBDO\Cms\Http\Middleware\Admin;

use Closure;
use Sentinel;

class BasicMiddleware
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
        if ( Sentinel::check() ) {
            return $next($request);
        }
        return redirect( \URL::to('icontrol') );
    }

}
