<?php

namespace BBDO\Cms\Http\Middleware\Admin;

use Closure;
use Sentinel;
use BBDO\Cms\Helpers\SentinelHelper;

class CheckPermissionMiddleware
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
        $hasPermission = $this->hasPermission(\Route::current(), Sentinel::getUser());
        if (  $hasPermission === true )  {
            return $next($request);
        }
        else return redirect()->back()->with("sentinel", "You dont have the rights to do: " . $hasPermission);
        
    }

      public function hasPermission($route, $user){
        // check if admin
        if ( $user->roles()->first() ) {
            if ( $user->roles()->first()->slug == 'admin' ) {
                return true;
            }
            else {
                return $this->checkPermission($route, $user);
            }
        }
    }

    public function checkPermission($route, $user) {
        // Check  the route versus the permissions of the user/role
        // based on: route name, action or first part of URI

        $action = 'view';
        $module_type = $route->getParameter('module_type');
        $first_uri = explode( '/', $route->uri() )[1]; // /items, /files
        switch ($route->getName()) {
            case 'sort':
                $action = 'publish';
                break;
            case 'overview':
                $action = 'view';
                break;
            case 'overviewdata':
                $action = 'view';
                break;
            case 'delete':
                $action = 'delete';
                break;
            case 'publish':
                $action = 'publish';
                break;
            default:
                $action = strtolower($route->getParameter('action'));
                break;
        }
        switch ($action) {
            case 'add':
                $action = 'create';
                break;
            case 'get':
                $action = 'create';
            default:
                break;
        }
        switch ($first_uri) {
            case 'files':
            $module_type = 'files';
            $action = 'manage';
            if ( $route->getName() == 'file_delete') 
                $action = 'delete';
            break;

            default:
                break;

        }
        //content module
        //general db config
        if(in_array( $module_type, config('cms.content_modules'))){
        $module_type = 'pages';
        }

        $permission = strtolower($module_type) . ($action != null ? '.'.strtolower($action):'');
        
        if ( $user->hasAccess($permission) ) return true;
        else return $permission;

    }
}
