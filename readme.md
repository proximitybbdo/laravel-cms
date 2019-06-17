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
    
   - `'default_locale' => 'nl-BE'` Default language. value from locales defined in config/app.php
   - `'default_cache_duration' => 60 * 24 * 30` Caching is done on PublicItem and some other element. Default is 30 days given in minutes.
   - `'enable_user_managment' => true` if enabled, admin will be able to manage other users.
   - `'enable_translation_manager' => true` if enabled, a translation tools will be present. It edits translations files, take care to add your lang file in the .gitignore or to keep track of the changes. 
   - `'modules' => array('CASES', ...)`  Modules selected here will be displayed in the cms's admin. 
   - `'content_modules' => array(),` If you want to share files & images across multiple modules (e.g. all content modules), you can add the module name in this array. All files will be then available under the module name "CONTENT".
   - `'custom_views' => array()` Define custom views when default one is not available - deprecated
   - `'files' => array()`   Default config for file upload.
   - `'image_types' => array()` types of image for upload. You can then define size, ... (/!\ generate_thumb should always be true)
   - '`user` => array()' Info for the user managment item in the menu. Do not remove it if you enabled user managment. 
   
   #### The modules :
   
   A module is composed with the key as name (in caps) and an array with its parameters.
   
    ```yaml
        'CASES' => array(
           'description' => 'Cases', //=> Name in the menu
           'single_item' => false, // Single item modules, will only create one item. No overview will be shown or available. (e.g. Homepage content module)
           'show_start_date' => false, // If true, you can define a start that and use getActiveItem() from the domain
           'show_end_date' => false, // If true, you can define a end that and use getActiveItem() from the domain
           'sortable' => true, // => Enable sorting in the admin
           'sort_by' => 'sort', //Key for sorting (field of table item). Use 'sort' if you defined sortable to true
           'sort_order' => 'ASC', // Default ordering. 
           'overview_custom' => false, //If true, the overview view will be overwritten for this module in admin.partials.overview.$module_type
           'preview' => ':lang/cases/:slug', //Link for the preview button
           'types' => [ 
               'typekey1'=>'typevalue1',  
               'typekey2'=>'typevalue2',
           ],
           //Easy classification list (non translated). Array of key values. (for example, featured item/non featured, homepage item ...)
           'fields' => array(
               ['form' => 'text', 'type' => 'intro', 'title' => 'Intro', 'editor' => 'editor-small'],
               ['form' => 'image', 'type' => 'image_header', 'title' => 'Header Image'],
               ['form' => 'images', 'type' => 'image_event', 'title' => 'Images (1948x912)', 'amount' => 10],
               ['form' => 'select', 'type' => 'background_color_class', 'title' => 'Background', 'options' => ['green'=>'bgGreen', 'red'=>'bgRed']]],
               /*
                form => type of field (should exists in admin.partials.input) text, textarea, select, image, images, file, files
                type => field name in the database. It can also be file or an image_type defined previously.
                title => text displayed in the admin
                editor => optional - type of editor : editor-small , editor--tiny
           
               */
               
           ),
           'links' => array( //You can link module to each other. 
               'PRODUCTS' => array(
                   'type' => 'multiple',// or single
                   'description' => 'Used products',
                   'overview_filter' => true,//will appear as a filter on the overview of the parent module
                   'input_type' => 'chosen',//chosen or ''
                   'add_item' => false,//this functionality had not been finished (yet)
               ),
           ),
           'field_validation' => array( //Validation rules for each field. the one prefixed by my_content are the one defined by you (translated fields). 
               'description' => 'required',
               'start_date' => 'required',
               'end_date' => 'required',

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
           'blocks' => array( // Some blocks can also be attached to an item. It's repeatable group of content fields in the module.
               'quote' => [
                   'description' => 'Quote',
                   'amount' => 1, //infinite when null
                   'fields' => [
                       ['type' => 'intro', 'form' => 'text', 'title' => 'Intro', 'editor' => 'editor--tiny'],
                       ['type' => 'author', 'form' => 'text', 'title' => 'Author'],
                       ['type' => 'image_1', 'form' => 'image', 'title' => 'Image 1'],
                   ]
                   //same config as content fields
               ],
               'case' => [
                   'description' => 'Case',
                   'amount' => null, //infinite when null
                   'fields' => [
                       ['type' => 'intro', 'form' => 'text', 'title' => 'Intro', 'editor' => 'editor--tiny'],
                   ],
                   'links' => [ // A block can contain link(s) to another module.
                       'CASES' => [
                           'description' => 'Featured case',
                           //'type' => 'single',
                           'type' => 'multiple',
                           'title' => 'Case',
                           'input_type' => 'chosen',//chosen or ''
                           'add_item' => false, //do not use
                       ],
                   ]
               ],
           ),
           /* ... */
       ),
      
       'EXPORT'    => [  //It's possible to add a custom export functionality. Add a controller with the export function and a route, and configure it bellow. Also custom links are allowed.
           'description'   => 'Export data',
           'nav_mode'      => 'route',//url or route. if empty link will not be used
           'url'       => '',
           'route'     => 'icontrol.export',
           'params'    => [],
           'always_visible_for_admin' => true,//if false, permission will be checked. It requires to explicitly provide permission through the roles permission system.
       ],
       
       'SETTINGS'  => array( //This enables the settings feature, allowing you to define global variables in the website.
               'description'   => 'Settings',
               'nav_mode'      => 'route',
               'route'     => 'icontrol.settings',
               'always_visible_for_admin' => true,
               'settings'  => [ // Here is the only part to change 
                   'isLive'  => [
                       'fields' => [
                           ['form' => 'radio', 'type' => 'isLive', 'title' => 'yes'],
                           ['form' => 'text', 'type' => 'isLive', 'title' => 'no']
                       ]
                   ]
               ]
           ),
    ```

   
   ### Fetching data in controller 
  
   In order to fetch datas from the CMS, your controller have to use the PublicItem Domain
     
     use BBDO\Cms\Domain\PublicItem;
     
     $domain = new PublicItem();
     $products = $domain->getAll("PRODUCTS", null, null, 'sort');  
     
   Feel free to explore the domain to learn more about the different methods.
   
   ### Getting data from domain's methods
   
   Following methods are available on the PublicItem model.
   
   - `->getContent('content_key')` will return the content field with name 'content_key'. Once this function is called, all other content will be cached to avoid multiple queries per content field. Will return an empty string when the content_key is not found.   
   - `getContentFileUrl('content_key')` Gets the url to a file from a field with name 'content_key'.
   - `->linksType('type')` will return you an iteratable list of linked items of type 'type'
   - `->linksFirst('type')` will return the first linked item of type 'type'
   - `->backLinksType('type')` will return you an iteratable list of items of type 'type' that have the current item as a link
   - `->backLinksFirst('type')` will return the first item of type 'type' that has the current item as a link
   - `->getBlocksType('type')` will return you all the blocks of type 'type' (iterable), on each block you can also call the above methods.
   
   - A lot of others methods are available, see in the model `BBDO\Cms\Models` to see more.    
     