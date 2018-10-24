# BBDO CMS

## Installation

   ### Composer.json config
   
   Just before the "require" key , add this
   
    "repositories": [
            {
                "type": "vcs",
                "url": "https://proximity-bbdo.git.beanstalkapp.com/bbdo-cms-laravel-package.git"
            }
        ],
        
   and then in require
    
    "bbdo/cms": "dev-master"     

   ### Vendor    
   Install the vendor for sentinel and the cms vendor 
    
    > php artisan vendor:publish
       
   And select the number of the vendor to install  (cms-* , sentinel service provider)
   
   cms-front-view will load controller & vue for the front end as a example. It'll show how to fetch data from the CMS in the view.
   
   ### Sentinel stuff
   
   In config/app.php
   
   - provider: `Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class,`
   - Alias: 
   
         'Activation' => Cartalyst\Sentinel\Laravel\Facades\Activation::class,
         'Reminder'   => Cartalyst\Sentinel\Laravel\Facades\Reminder::class,
         'Sentinel'   => Cartalyst\Sentinel\Laravel\Facades\Sentinel::class,

   ### Intervention Image
   
   - provider: `Intervention\Image\ImageServiceProvider::class,`
   
   - Alias: `'Image' => Intervention\Image\Facades\Image::class`
   
   ### Database
   
   Remove the user & password migration file who are present by default in the new Laravel project (as it's included in the cms package too)
   
   Run `php artisan migrate` and `php artisan db:seed` 


   ### Update middleware (in app/Http/Middleware)
   
   
   -  VerifyCsrfToken : update the $except array to add some route as seen on the next example :
   
           protected $except = [
               '*/api/*',
               'icontrol/items/*',
               'icontrol/geturlfriendlytext',
               'icontrol/files/*'  
           ];
   
   - RedirectIfAuthenticated : Update the redirection route from /home to /icontrol/dashboard
   
            if (Auth::guard($guard)->check()) {
                       return redirect('/icontrol/dashboard');
            }
   
   ### Update config
   
   in config/auth.php, update the model user 
   
         'providers' => [
           'users' => [
               'driver' => 'eloquent',
               'model' => \BBDO\Cms\Models\User::class,
           ],
           
   in config/app.php, add the locales
   
        'locales' => array(
                array('short' => 'nl-BE', 'long' => 'Nederlands'),
                array('short' => 'fr-BE', 'long' => 'Français'),
            ),        
 
   ### Extend the cms with custom controller
   
   Extra item can be added in the menu with the route item
   
            'EXPORT'    => [
               'description'   => 'Export data',
               'nav_mode'      => 'route',//url or route. if empty link will not be used
               'url'       => '',
               'route'     => 'icontrol.export',
               'params'    => []
            ]
       
   The route can then be defined in your project route. These middleware will do the usual check on this route and the user should have the permission {module}.vue
   
        Route::group(['prefix' => 'icontrol', 'middleware' => [
           \BBDO\Cms\Http\Middleware\Admin\BasicMiddleware::class,
           \BBDO\Cms\Http\Middleware\Admin\CheckPermissionMiddleware::class
        ]], function () {
   
           Route::get('export', [
              'uses'   => 'Admin\Export',
              'as'     => 'icontrol.export'
           ]);
   
        });
    
 ## Using in dev mode
 
   If you use this package in dev mode, be sure to have your composer.json with the cms namespace in your psr-4
   
    "autoload": {
           "classmap": [
               "database/seeds",
               "database/factories"
           ],
           "psr-4": {
               "App\\": "app/",
               "BBDO\\Cms\\": "packages/bbdo/cms/src"
           }
       }, 
       
   Also, adding `BBDO\Cms\CmsServiceProvider::class,` in the $provider in config/app.php can fix loading issue.   