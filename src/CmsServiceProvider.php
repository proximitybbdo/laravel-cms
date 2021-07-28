<?php

namespace BBDOCms;

use BBDOCms\Console\Commands\AddAdminUser;
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
        ], 'cms-config');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'bbdocms');

        $this->publishes([
            __DIR__ . '/resources/assets' => public_path('admin'),
        ], 'cms-asset');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'bbdocms');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddAdminUser::class,
            ]);
        }

        $this->registerSeedsFrom(__DIR__ . '/database/seeder');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/Helpers/function.php';
    }

    protected function registerSeedsFrom($path)
    {
        foreach (glob($path . '/*.php') as $filename) {
            include $filename;
            $classes = get_declared_classes();
            $class = end($classes);

            $command = \Request::server('argv', null);
            if (is_array($command)) {
                $command = implode(' ', $command);
                if ($command == "artisan db:seed") {
                    \Artisan::call('db:seed', ['--class' => $class]);
                }
            }
        }
    }
}
