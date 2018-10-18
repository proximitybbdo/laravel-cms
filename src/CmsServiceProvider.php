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
            __DIR__ . '/config/cms.php' => config_path('cms.php'),
        ],'cms-config');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'cms-translation');

        $this->publishes([
            __DIR__ . '/resources/assets' => resource_path('assets/cms'),
        ], 'cms-asset');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'bbdocms');


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
    }
}
