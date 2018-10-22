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
   
   ### Sentinel stuff
   
   In config/app.php
   
   - provider: `Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class,`
   - Alias: 
   
         'Activation' => Cartalyst\Sentinel\Laravel\Facades\Activation::class,
         'Reminder'   => Cartalyst\Sentinel\Laravel\Facades\Reminder::class,
         'Sentinel'   => Cartalyst\Sentinel\Laravel\Facades\Sentinel::class,

   ### Database
   
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