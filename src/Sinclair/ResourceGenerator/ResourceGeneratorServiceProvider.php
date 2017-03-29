<?php

namespace Sinclair\ResourceGenerator;

use Illuminate\Support\ServiceProvider;
use Sinclair\CrudController\Providers\CrudControllerServiceProvider;
use Sinclair\Track\TrackServiceProvider;

/**
 * Class ResourceGeneratorServiceProvider
 * @package Sinclair\ResourceGenerator
 */
class ResourceGeneratorServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/stubs' => resource_path('stubs/vendor/resource-generator'),
        ]);

        $this->publishes([ __DIR__ . '/../../config' => config_path() ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app[ 'command.resource.create' ] = $this->app->singleton(
            function ()
            {
                return new CreateResource;
            }
        );

        $this->app[ 'command.resource.remove' ] = $this->app->singleton(
            function ()
            {
                return new RemoveResource();
            }
        );

        $this->commands('command.resource.create', 'command.resource.remove');

        $this->app->register(CrudControllerServiceProvider::class);

        $this->app->register(TrackServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'command.resource.create', 'command.resource.remove' ];
    }
}
