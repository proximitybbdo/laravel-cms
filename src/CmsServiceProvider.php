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
        ], 'cms-config');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'bbdocms');

        $this->publishes([
            __DIR__ . '/resources/assets' => public_path('admin'),
        ], 'cms-asset');

        $this->publishes([
            __DIR__ . '/resources/views/front' => resource_path('views/front'),
            __DIR__ . '/app/Http/Controllers/BBDOHomeController.php' => app_path('Http/Controllers/BBDOHomeController.php'),
        ], 'cms-front-view');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'bbdocms');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddAdminUser::class,
            ]);
        }

        $this->registerSeedsFrom(__DIR__ . '/database/seeds');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/app/Helpers/function.php';
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
