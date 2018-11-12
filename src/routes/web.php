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

Route::group(['prefix' => 'icontrol', 'middleware' => 'web', 'namespace' => '\BBDO\Cms\Http\Controllers\Admin'], function () {
    Route::get('logout', [
        'uses' => 'SentinelController@logout',
        'as' => 'sentinel.logout'
    ]);

    Route::get('/', [
        'uses' => 'SentinelController@showLoginForm',
        'as' => 'login'
    ]);

    Route::get('login', 'SentinelController@showLoginForm');

    Route::post('login', [
        'uses' => 'SentinelController@login',
        'as' => 'sentinel.postLogin'
    ]);


    Route::group(['middleware' => \BBDO\Cms\Http\Middleware\Admin\BasicMiddleware::class], function () {
        Route::get('dashboard', [
            'uses' => 'AdminController@index',
            'as' => 'dashboard'
        ]);

        Route::get('clearcache', [
            'uses' => 'AdminController@getClearcache',
            'as' => 'icontrol.clearcache'
        ]);

        Route::post('clearcache', [
            'uses' => 'AdminController@postClearcache',
            'as' => 'icontrol.storeClearcache'
        ]);

        Route::post('geturlfriendlytext', [
            'uses' => 'HelperController@postUrlFriendlyText',
            'as' => 'icontrol.postUrlFriendlyText'
        ]);

        Route::get('user/password', 'UserController@editPassword')->name('icontrol.user.editPassword');
        Route::post('user/password', 'UserController@updatePassword')->name('icontrol.user.updatePassword');

        Route::get('translations', 'TranslationController@index')->name('icontrol.translation.index');
        Route::get('translations/{lang}', 'TranslationController@show')->name('icontrol.translation.show');
        Route::post('translations/{lang}', 'TranslationController@update')->name('icontrol.translation.update');

        Route::group(['middleware' => \BBDO\Cms\Http\Middleware\Admin\AdminMiddleware::class], function () {
            Route::get('roles', 'SentinelController@showRolesForm');
            Route::post('roles', 'SentinelController@assign_roles');

            Route::get('/user', 'UserController@index')->name('icontrol.user.index');
            Route::get('/user/create', 'UserController@create')->name('icontrol.user.create');
            Route::post('/user/store', 'UserController@store')->name('icontrol.user.store');
            Route::get('/user/edit/{userId}', 'UserController@edit')->name('icontrol.user.edit');
            Route::post('/user/update/{userId}', 'UserController@update')->name('icontrol.user.update');
            Route::get('/user/delete/{userId}', 'UserController@delete')->name('icontrol.user.delete');
        });

        Route::group(['middleware' => \BBDO\Cms\Http\Middleware\Admin\CheckPermissionMiddleware::class], function () {
            Route::group(
                [
                    'prefix' => 'items',
                ],
                function () {
                    Route::get('{module_type}/overview', 'ItemController@getOverview')->name('overview');
                    Route::get('{module_type}/overview/{link_id}', 'ItemController@getOverview')->name('overview');

                    Route::post('{module_type}/overviewdata', 'ItemController@postOverviewData')->name('overviewdata');
                    Route::post('{module_type}/publish', 'ItemController@postPublish')->name('publish');
                    Route::post('{module_type}/featured', 'ItemController@postFeatured')->name('featured');
                    Route::post('{module_type}/delete', 'ItemController@postDelete')->name('delete');
                    Route::post('{module_type}/sortitems', 'ItemController@postSortPost')->name('sort');
                    Route::post('{module_type}/renderblock', 'ItemController@postRenderBlock')->name('render_block');

                    Route::get('custom_view/{module_type}/{action}/{lang}/{view_name}/{id}/{back_module_type}/', 'ItemController@getAddItemCustomView');
                    Route::get('custom_view/{module_type}/{action}/{lang}/{view_name}', 'ItemController@getAddItemCustomView');
                    Route::get('{module_type}/copylang/{id}/{source_lang}/{destination_lang}', 'ItemController@getCopyLangItem')->name('copylang');
                    Route::get('{module_type}/revert/{lang}/{id}', 'ItemController@getRevertItem')->name('revert');
                    Route::get('{module_type}/{action}/{lang}/{id}/{back_module_type}/{back_id}', 'ItemController@getAddItem');
                    Route::post('{module_type}/{action}/{lang}/{id}/{back_module_type}/{back_id}', 'ItemController@getAddItem');
                    Route::get('{module_type}/{action}/{lang}/{id}/{back_module_type}', 'ItemController@getAddItem');
                    Route::post('{module_type}/{action}/{lang}/{id}/{back_module_type}', 'ItemController@getAddItem');
                    Route::get('{module_type}/{action}/{lang}/{id}', 'ItemController@getAddItem')->name('items.edit');
                    Route::get('{module_type}/{action}/{lang}', 'ItemController@getAddItem')->name('items.add');
                    //Route::post('{module_type}/{action}/{lang}', 'ItemController@getAddItem');

                    Route::get('{module_type}/{action}', 'ItemController@getAddItem');
                    Route::post('{module_type}/{action}/{lang?}', 'ItemController@store');
                    Route::post('{module_type}/{action}/{lang}/{id}', 'ItemController@store')->name('items.update');
                }
            );

            Route::get('files/getimagecontainer/{id}/{type}', 'FilesController@getImageContainer');
            Route::get('files/{manager_type}/manager', 'FilesController@getManager');
            Route::get('files/{manager_type}/manager/{module_type}/{input_id}/{value}', 'FilesController@getManager');
            Route::get('files/{manager_type}/manager/{module_type}/{input_type}/{input_id}/{value}', 'FilesController@getManager');
            Route::get('files/{manager_type}/popupmanager/{module_type}/{input_type}/{input_id}', 'FilesController@getPopupManager');
            Route::get('files/{manager_type}/popupmanager/{module_type}/{input_type}/{input_id}/{value}', 'FilesController@getPopupManager');
            Route::post('files/{manager_type}/upload', 'FilesController@postUpload');
            Route::post('files/{manager_type}/{module}/upload', 'FilesController@postUpload');
            Route::post('files/{manager_type}/{module}/{input_type}/upload', 'FilesController@postUpload');
            Route::get('files/{manager_type}/getlist/{mode}', 'FilesController@getFiles');
            Route::get('files/{manager_type}/getlist/{mode}/{input_id}/{module_type}', 'FilesController@getFiles');
            Route::get('files/{manager_type}/getlist/{mode}/{input_id}/{module_type}/{input_type}', 'FilesController@getFiles');
            Route::post('files/file_assign_cat', 'FilesController@postAssignCategory');
            Route::post('files/remove', 'FilesController@postRemove')->name('file_delete');
            Route::post('files/{manager_type}/purge', 'FilesController@postPurge');
        });
    });
});
