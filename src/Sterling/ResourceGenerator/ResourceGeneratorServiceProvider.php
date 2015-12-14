<?php namespace Sterling\ResourceGenerator;

use App\Console\Commands\CreateResource;
use App\Console\Commands\RemoveResource;
use Illuminate\Support\ServiceProvider;

class ResourceGeneratorServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->setWhatPublishes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['command.resource.create'] = $this->app->share(
            function ($app) {
                return new CreateResource;
            }
        );

        $this->app['command.resource.remove'] = $this->app->share(
            function ($app) {
                return new RemoveResource();
            }
        );

        $this->commands('command.resource.create', 'command.resource.remove');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'command.resource.create', 'command.resource.remove'];
    }

    private function setWhatPublishes()
    {
        $this->publishes([
            __DIR__ . '/stubs'                  => base_path('resources/stubs/vendor/resource-generator')
        ]);
    }

}
