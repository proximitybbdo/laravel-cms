<?php

namespace BBDO\Cms;

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
            __DIR__.'/../config/cms.php' => config_path('cms.php'),
        ],'config');

        $this->loadRoutesFrom(__DIR__.'/../routes');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'bbdocms');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/bbdocms'),
        ], 'translation');

        $this->publishes([
            __DIR__.'/../resources/assets' => resource_path('assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bbdocms');

        if ($this->app->runningInConsole()) {
            $this->commands([
                //Add here classes to commands classes created
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
