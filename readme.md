# BBDO CMS

## Installation

   ### Vendor    
   Install the vendor for sentinel and the cms vendor 
    
    > php artisan vendor:publish
       
   And select the number of the vendor to install 

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