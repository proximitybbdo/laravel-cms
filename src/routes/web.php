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

Route::group(['prefix' => 'icontrol', 'middleware' => 'web'], function() {

  Route::get('logout', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@logout')->name('logout');
  Route::get('/', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@showLoginForm')->name('login');
  Route::get('login', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@showLoginForm')->name('login');
  Route::post('login', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@login');

  
  Route::group( ['middleware' => \BBDO\Cms\Http\Middleware\Admin\BasicMiddleware::class], function() {
    
    Route::get('dashboard', '\BBDO\Cms\Http\Controllers\Admin\AdminController@index')->name('dashboard');
    Route::get('clearcache', '\BBDO\Cms\Http\Controllers\Admin\AdminController@getClearcache')->name('clearcache');
    Route::post('clearcache', '\BBDO\Cms\Http\Controllers\Admin\AdminController@postClearcache')->name('clearcache');;
    Route::post('geturlfriendlytext','\BBDO\Cms\Http\Controllers\Admin\HelperController@post_urlfriendlytext')->name('post_urlfriendlytext');;

    Route::group( ['middleware' => \BBDO\Cms\Http\Middleware\Admin\AdminMiddleware::class], function(){
      Route::get('roles', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@showRolesForm');
      Route::post('roles', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@assign_roles');
      Route::get('register', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@showRegistrationForm')->name('register');
      Route::post('register', '\BBDO\Cms\Http\Controllers\Admin\SentinelController@register');
    });

    Route::group( ['middleware' => \BBDO\Cms\Http\Middleware\Admin\CheckPermissionMiddleware::class], function(){
      Route::group(
        [
          'prefix' => 'items',
        ], function() {
        Route::get('{module_type}/overview', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_overview')->name('overview');
        Route::get('{module_type}/overview/{link_id}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_overview')->name('overview');

        Route::post('{module_type}/overviewdata', '\BBDO\Cms\Http\Controllers\Admin\ItemController@post_overview_data')->name('overviewdata');
        Route::post('{module_type}/publish', '\BBDO\Cms\Http\Controllers\Admin\ItemController@post_publish')->name('publish');
        Route::post('{module_type}/featured', '\BBDO\Cms\Http\Controllers\Admin\ItemController@post_featured')->name('featured');
        Route::post('{module_type}/delete', '\BBDO\Cms\Http\Controllers\Admin\ItemController@post_delete')->name('delete');
        Route::post('{module_type}/sortitems', '\BBDO\Cms\Http\Controllers\Admin\ItemController@post_sort_post')->name('sort');
        Route::post('{module_type}/renderblock', '\BBDO\Cms\Http\Controllers\Admin\ItemController@post_render_block')->name('render_block');

        Route::get('custom_view/{module_type}/{action}/{lang}/{view_name}/{id}/{back_module_type}/', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item_custom_view');
        Route::get('custom_view/{module_type}/{action}/{lang}/{view_name}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item_custom_view');
        Route::get('{module_type}/copylang/{id}/{source_lang}/{destination_lang}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_copylang_item')->name('copylang');
        Route::get('{module_type}/revert/{lang}/{id}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_revert_item')->name('revert');
        Route::get('{module_type}/{action}/{lang}/{id}/{back_module_type}/{back_id}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item');
        Route::post('{module_type}/{action}/{lang}/{id}/{back_module_type}/{back_id}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item');
        Route::get('{module_type}/{action}/{lang}/{id}/{back_module_type}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item');
        Route::post('{module_type}/{action}/{lang}/{id}/{back_module_type}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item');
        Route::get('{module_type}/{action}/{lang}/{id}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item')->name('items.edit');
        Route::get('{module_type}/{action}/{lang}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item');
        //Route::post('{module_type}/{action}/{lang}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item');

        Route::get('{module_type}/{action}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@get_add_item');
        Route::post('{module_type}/{action}/{lang?}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@store');
        Route::post('{module_type}/{action}/{lang}/{id}', '\BBDO\Cms\Http\Controllers\Admin\ItemController@store')->name('items.update');
      });
      
      Route::get('files/getimagecontainer/{id}/{type}','\BBDO\Cms\Http\Controllers\Admin\FilesController@get_image_container');
      Route::get('files/{manager_type}/manager','\BBDO\Cms\Http\Controllers\Admin\FilesController@get_manager');
      Route::get('files/{manager_type}/manager/{module_type}/{input_id}/{value}', '\BBDO\Cms\Http\Controllers\Admin\FilesController@get_manager');
      Route::get('files/{manager_type}/manager/{module_type}/{input_type}/{input_id}/{value}', '\BBDO\Cms\Http\Controllers\Admin\FilesController@get_manager');
      Route::get('files/{manager_type}/popupmanager/{module_type}/{input_type}/{input_id}', '\BBDO\Cms\Http\Controllers\Admin\FilesController@get_popup_manager');
      Route::get('files/{manager_type}/popupmanager/{module_type}/{input_type}/{input_id}/{value}', '\BBDO\Cms\Http\Controllers\Admin\FilesController@get_popup_manager');
      Route::post('files/{manager_type}/upload', '\BBDO\Cms\Http\Controllers\Admin\FilesController@post_upload');
      Route::post('files/{manager_type}/{module}/upload', '\BBDO\Cms\Http\Controllers\Admin\FilesController@post_upload');
      Route::post('files/{manager_type}/{module}/{input_type}/upload', '\BBDO\Cms\Http\Controllers\Admin\FilesController@post_upload');
      Route::get('files/{manager_type}/getlist/{mode}', '\BBDO\Cms\Http\Controllers\Admin\FilesController@get_files');
      Route::get('files/{manager_type}/getlist/{mode}/{input_id}/{module_type}', '\BBDO\Cms\Http\Controllers\Admin\FilesController@get_files');
      Route::get('files/{manager_type}/getlist/{mode}/{input_id}/{module_type}/{input_type}', '\BBDO\Cms\Http\Controllers\Admin\FilesController@get_files');
      Route::post('files/file_assign_cat', '\BBDO\Cms\Http\Controllers\Admin\FilesController@post_assign_cat');
      Route::post('files/remove', '\BBDO\Cms\Http\Controllers\Admin\FilesController@post_remove')->name('file_delete');
      Route::post('files/{manager_type}/purge', '\BBDO\Cms\Http\Controllers\Admin\FilesController@post_purge');
    });
  });
});