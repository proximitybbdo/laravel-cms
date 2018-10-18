<?php

namespace BBDO\Cms;

use BBDO\Cms\Console\Commands\AddAdminUser;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/src/config/cms.php' => config_path('cms.php'),
        ],'config');

        $this->loadRoutesFrom(__DIR__.'/src/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/src/database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/src/resources/lang', 'bbdocms');

        $this->publishes([
            __DIR__.'/src/resources/lang' => resource_path('lang/vendor/bbdocms'),
        ], 'translation');

        $this->publishes([
            __DIR__.'/src/resources/assets' => resource_path('assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/src/resources/views', 'bbdocms');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddAdminUser::class
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
