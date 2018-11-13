# BBDO CMS

## Installation

   ### Composer.json config
   
   Just before the "require" key , add this
   
    "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/proximitybbdo/laravel-cms.git"
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
   
   
   
 ## Usage
 
   ### Setup config file
   
   A default config file is provided (key cms-config when you do vendor:publish)
   
   #### General settings
    
   - `'default_locale' => 'nl-BE'` Default language. value from locales defined in confif/app.php
   - `'default_cache_duration' => 60 * 24 * 30` Caching is done on PublicItem and some other element. Default is 30 days given in minutes.
   - `'enable_user_managment' => true` if enabled, admin will be able to manage other user.
   - `'enable_translation_manager' => true` if enabled, a translation tools will be present. It edit translations files so take care to add you lang file in the .gitignore or to keep track of the change. 
   - `'modules' => array('CASES', ...)`  Modules selected here will be displayed in the cms's admin. 
   - `'content_modules' => array(),` If for some modules you want to share the files & images across all, you can add the modules name in this array. All files will be then available under the module name "CONTENT"
   - `'custom_views' => array()` Define custom views when default one is not availbale
   - `'files' => array()`   Default config for file upload.
   - `'image_types' => array()` types of image for upload. You can then define size, ... (/!\ generate_thumb should always be true)
   - '`user` => array()' Info for the user managment item in the menu. Do not remove it if you enabled user managment. 
   
   #### The modules :
   
   A modules is composed with the key as name (in caps) and an array with its parameters.
   
    
        'CASES' => array(
           'description' => 'Cases', //=> Name in the menu
           'sortable' => true, // => Enable sorting in the admin
           'show_start_date' => false, // If true, you can define a start that and use getActiveItem() from the domain
           'sort_by' => 'sort', //Key for sorting. use sort if you defined sortable to true
           'sort_order' => 'ASC', // Default ordering. 
           'overview_custom' => false, //If true, the overview view will be overwritten for this module in admin.partials.overview.$module_type
           'preview' => ':lang/cases/:slug', //Link for the preview button
           'fields' => array(
               ['form' => 'text', 'type' => 'intro', 'title' => 'Intro', 'editor' => 'editor-small'],
               ['form' => 'image', 'type' => 'image_header', 'title' => 'Header Image'],
               /*
                form => type of field (should exists in admin.partials.input
                type => it's the field name in the database. It can also be file or an image_type defined previously.
                title => text displayed in the admin
                editor => type of editor (can be omitted) : editor-small , editor--tiny
           
               */
               
           ),
           'links' => array( //You can link module to each other. 
               'PRODUCTS' => array(
                   'type' => 'multiple',// or single
                   'description' => 'Used products',
                   'overview_filter' => true,
                   'input_type' => 'chosen',//chosen or ''
                   'add_item' => false,
               ),
           ),
           'field_validation' => array( //Validation rules for each field. the one prefixed by my_content are default one pushed by the cms, other one are the one defined by you.
               'description' => 'required',
               'my_content.seo_title' => 'required',
               'my_content.title' => 'required',
           ),
           'field_validation_nicenames' => array( //Nice name for validation error message
               'description' => 'description',
               'my_content.seo_title' => 'seo title',
               'my_content.title' => 'title',
           ),
       ),
       'PRODUCTS' => array(
           /* ... */
           'blocks' => array( // Some blocks can also be attached to an item. It's repeatable "module" in the module.
               'quote' => [
                   'description' => 'Quote',
                   'amount' => 1, //infinite when null
                   'fields' => [
                       ['type' => 'intro', 'form' => 'text', 'title' => 'Intro', 'editor' => 'editor--tiny'],
                       ['type' => 'author', 'form' => 'text', 'title' => 'Author'],
                       ['type' => 'image_1', 'form' => 'image', 'title' => 'Image 1'],
                   ]
               ],
               'case' => [
                   'description' => 'Case',
                   'amount' => null, //infinite when null
                   'fields' => [
                       ['type' => 'intro', 'form' => 'text', 'title' => 'Intro', 'editor' => 'editor--tiny'],
                   ],
                   'links' => [ // A block can then link to another module.
                       'CASES' => [
                           'description' => 'Featured case',
                           //'type' => 'single',
                           'type' => 'multiple',
                           'title' => 'Case',
                           'input_type' => 'chosen',//chosen or ''
                           'add_item' => false,
                       ],
                   ]
               ],
           ),
           /* ... */
       ),
      
       'EXPORT'    => [ //It's also possible to add extra link or route. It's here possible to extend the cms with custom controller in the project
           'description'   => 'Export data',
           'nav_mode'      => 'route',//url or route. if empty link will not be used
           'url'       => '',
           'route'     => 'icontrol.export',
           'params'    => [],
           'always_visible_for_admin' => true,//if false, only permission will be checked. It require to explicitly provide permission
       ]
   

   
   ### Fetching data in controller 
  
   In order to fetch datas from the CMS, your controller have to use the PublicItem Domain
     
     use BBDO\Cms\Domain\PublicItem;
     
     $domain = new PublicItem();
     $products = $domain->getAll("PRODUCTS", null, null, 'sort');  
     
   Feel free to explore the domain to learn more about the different method.
   
   ### Getting data from domain's methods
   
   On `$products` you can call different method to get the items defined in the config file.
   
   - `->getContent($itemName)` will return one item. If $itemName is ommited, it'll return all the keys.     
   - `->blocks()` will return you all the block (iterable), on each item in the block you can also call getContent()
   - `->links` will return you the linked item.
   - A lot of others methods are available, see in the model `BBDO\Cms\Models` to see more.    
     