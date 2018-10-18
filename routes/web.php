<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::group([
  'prefix' => LaravelLocalization::setLocale(),
  'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]
  ], function() {

  Route::get('/', 'HomeController@index')->name('index');
  Route::get('/products', 'HomeController@product_index')->name('products_index');
  Route::get('/products/{slug}', 'HomeController@product_detail')->name('product_detail');
});

Route::group(['prefix' => 'icontrol'], function() {

  Route::get('logout', 'Admin\SentinelController@logout')->name('logout');
  Route::get('/', 'Admin\SentinelController@showLoginForm')->name('login');
  Route::get('login', 'Admin\SentinelController@showLoginForm')->name('login');
  Route::post('login', 'Admin\SentinelController@login');

  
  Route::group( ['middleware' => 'admin.basic'], function() {
    
    Route::get('dashboard', 'Admin\AdminController@index')->name('dashboard');
    Route::get('clearcache', 'Admin\AdminController@get_clearcache')->name('clearcache');
    Route::post('clearcache', 'Admin\AdminController@post_clearcache')->name('clearcache');;
    Route::post('geturlfriendlytext','Admin\HelperController@post_urlfriendlytext')->name('post_urlfriendlytext');;

    Route::group( ['middleware' => 'admin.admin'], function(){
      Route::get('roles', 'Admin\SentinelController@showRolesForm');
      Route::post('roles', 'Admin\SentinelController@assign_roles');
      Route::get('register', 'Admin\SentinelController@showRegistrationForm')->name('register');
      Route::post('register', 'Admin\SentinelController@register');
    });

    Route::group( ['middleware' => 'admin.checkPermission'], function(){
      Route::group(
        [
          'prefix' => 'items',
        ], function() {
        Route::get('{module_type}/overview', 'Admin\ItemController@get_overview')->name('overview');
        Route::get('{module_type}/overview/{link_id}', 'Admin\ItemController@get_overview')->name('overview');

        Route::post('{module_type}/overviewdata', 'Admin\ItemController@post_overview_data')->name('overviewdata');
        Route::post('{module_type}/publish', 'Admin\ItemController@post_publish')->name('publish');
        Route::post('{module_type}/featured', 'Admin\ItemController@post_featured')->name('featured');
        Route::post('{module_type}/delete', 'Admin\ItemController@post_delete')->name('delete');
        Route::post('{module_type}/sortitems', 'Admin\ItemController@post_sort_post')->name('sort');
        Route::post('{module_type}/renderblock', 'Admin\ItemController@post_render_block')->name('render_block');

        Route::get('custom_view/{module_type}/{action}/{lang}/{view_name}/{id}/{back_module_type}/', 'Admin\ItemController@get_add_item_custom_view');
        Route::get('custom_view/{module_type}/{action}/{lang}/{view_name}', 'Admin\ItemController@get_add_item_custom_view');
        Route::get('{module_type}/copylang/{id}/{source_lang}/{destination_lang}', 'Admin\ItemController@get_copylang_item')->name('copylang');
        Route::get('{module_type}/revert/{lang}/{id}', 'Admin\ItemController@get_revert_item')->name('revert');
        Route::get('{module_type}/{action}/{lang}/{id}/{back_module_type}/{back_id}', 'Admin\ItemController@get_add_item');
        Route::post('{module_type}/{action}/{lang}/{id}/{back_module_type}/{back_id}', 'Admin\ItemController@get_add_item');
        Route::get('{module_type}/{action}/{lang}/{id}/{back_module_type}', 'Admin\ItemController@get_add_item');
        Route::post('{module_type}/{action}/{lang}/{id}/{back_module_type}', 'Admin\ItemController@get_add_item');
        Route::get('{module_type}/{action}/{lang}/{id}', 'Admin\ItemController@get_add_item')->name('items.edit');
        Route::get('{module_type}/{action}/{lang}', 'Admin\ItemController@get_add_item');
        Route::post('{module_type}/{action}/{lang}', 'Admin\ItemController@get_add_item');

        Route::get('{module_type}/{action}', 'Admin\ItemController@get_add_item');
        Route::post('{module_type}/{action}/{lang?}', 'Admin\ItemController@store');
        Route::post('{module_type}/{action}/{lang}/{id}', 'Admin\ItemController@store')->name('items.update');
      });
      
      Route::get('files/getimagecontainer/{id}/{type}','Admin\FilesController@get_image_container');
      Route::get('files/{manager_type}/manager','Admin\FilesController@get_manager');
      Route::get('files/{manager_type}/manager/{module_type}/{input_id}/{value}', 'Admin\FilesController@get_manager');
      Route::get('files/{manager_type}/manager/{module_type}/{input_type}/{input_id}/{value}', 'Admin\FilesController@get_manager');
      Route::get('files/{manager_type}/popupmanager/{module_type}/{input_type}/{input_id}', 'Admin\FilesController@get_popup_manager');
      Route::get('files/{manager_type}/popupmanager/{module_type}/{input_type}/{input_id}/{value}', 'Admin\FilesController@get_popup_manager');
      Route::post('files/{manager_type}/upload', 'Admin\FilesController@post_upload');
      Route::post('files/{manager_type}/{module}/upload', 'Admin\FilesController@post_upload');
      Route::post('files/{manager_type}/{module}/{input_type}/upload', 'Admin\FilesController@post_upload');
      Route::get('files/{manager_type}/getlist/{mode}', 'Admin\FilesController@get_files');
      Route::get('files/{manager_type}/getlist/{mode}/{input_id}/{module_type}', 'Admin\FilesController@get_files');
      Route::get('files/{manager_type}/getlist/{mode}/{input_id}/{module_type}/{input_type}', 'Admin\FilesController@get_files');
      Route::post('files/file_assign_cat', 'Admin\FilesController@post_assign_cat');
      Route::post('files/remove', 'Admin\FilesController@post_remove')->name('file_delete');
      Route::post('files/{manager_type}/purge', 'Admin\FilesController@post_purge');
    });
  });
});