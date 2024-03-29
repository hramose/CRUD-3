<?php

namespace Sone\CRUD;

use Route;
use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    use CrudUsageStats;

    const VERSION = '1.0.0';

    protected $commands = [
        \Sone\CRUD\app\Console\Commands\Install::class,
        \Sone\CRUD\app\Console\Commands\Publish::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $_SERVER['SONE_CRUD_VERSION'] = $this::VERSION;
        $customViewsFolder = resource_path('views/vendor/sone/crud');

        // LOAD THE VIEWS

        // - first the published/overwritten views (in case they have any changes)
        if (file_exists($customViewsFolder)) {
            $this->loadViewsFrom($customViewsFolder, 'crud');
        }
        // - then the stock views that come with the package, in case a published view might be missing
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'crud');

        // PUBLISH FILES

        // publish lang files
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/sone')], 'lang');

        // publish views
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/sone/crud')], 'views');

        // publish config file
        $this->publishes([__DIR__.'/config' => config_path()], 'config');

        // publish public Sone CRUD assets
        $this->publishes([__DIR__.'/public' => public_path('vendor/sone')], 'public');

        // publish custom files for elFinder
        $this->publishes([
            __DIR__.'/config/elfinder.php'      => config_path('elfinder.php'),
            __DIR__.'/resources/views-elfinder' => resource_path('views/vendor/elfinder'),
        ], 'elfinder');

        // AUTO PUBLISH
        if (\App::environment('local')) {
            if ($this->shouldAutoPublishPublic()) {
                \Artisan::call('vendor:publish', [
                    '--provider' => 'Sone\CRUD\CrudServiceProvider',
                    '--tag' => 'public',
                ]);
            }
        }

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/sone/crud.php',
            'sone.crud'
        );

        $this->sendUsageStats();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('CRUD', function ($app) {
            return new CRUD($app);
        });

        // register the artisan commands
        $this->commands($this->commands);

        // map the elfinder prefix
        if (! \Config::get('elfinder.route.prefix')) {
            \Config::set('elfinder.route.prefix', \Config::get('sone.base.route_prefix').'/elfinder');
        }
    }

    public static function resource($name, $controller, array $options = [])
    {
        return new CrudRouter($name, $controller, $options);
    }

    /**
     * Checks to see if we should automatically publish
     * vendor files from the public tag.
     *
     * @return bool
     */
    private function shouldAutoPublishPublic()
    {
        $crudPubPath = public_path('vendor/sone/crud');

        if (! is_dir($crudPubPath)) {
            return true;
        }

        return false;
    }
}
