<?php

namespace Sinclair\ResourceGenerator;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;

class CreateResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:create
                            {resource : The name of the resource you would like to create}
                            {--all : Create all files}
                            {--migration : Create a migration}
                            {--seeder : Create a seeder}
                            {--create-request : Create a create request}
                            {--update-request : Create a update request}
                            {--repository : Create a repository}
                            {--controller : Create a controller}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all the classes and interfaces required for a resource';

    /**
     * The name of the resource to be created
     *
     * @var
     */
    protected $resource;

    protected $objects = [
        'Model'               => 'Models',
        'ModelInterface'      => 'Contracts',
        'ModelFacade'         => 'Facades',
        'Repository'          => 'Repositories',
        'RepositoryInterface' => 'Contracts',
        'RepositoryFacade'    => 'Facades',
        'Controller'          => 'Http/Controllers',
    ];

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Composer $composer
     *
     * @return mixed
     */
    public function handle(Composer $composer)
    {
        $this->resource = $this->argument('resource');

        $model = studly_case($this->resource);

        $str_to_lower_model = snake_case($this->resource);

        $this->makeMigration($str_to_lower_model);
        $this->makeSeeder($model);
        $this->makeCreateRequest($model);
        $this->makeUpdateRequest($model);

        foreach ($this->objects as $stub => $directory)
            $this->buildClass($stub, $directory);

        $this->askToMigrate($composer);

        $this->askToSeed($composer, $model);

        $this->showHelp($model, $str_to_lower_model);
    }

    private function all()
    {
        return $this->option('all') || sizeof(array_filter($this->option())) == 0;
    }

    /**
     * @param $stub
     * @param $directory
     */
    private function buildClass($stub, $directory)
    {
        if ($this->getOption($stub) || $this->all())
        {
            $this->checkDirectory($directory);

            $this->handleFile($stub, $directory);
        }
    }

    /**
     * @param $stub
     *
     * @return string
     */
    private function makeClassName($stub)
    {
        $filename = studly_case($this->resource);

        if (str_contains($stub, 'Repository'))
            $filename .= 'Repository';

        if (str_contains($stub, 'Controller'))
            $filename .= 'Controller';

        return $filename;
    }

    /**
     * @param $model
     * @param $str_to_lower_model
     */
    private function showHelp($model, $str_to_lower_model)
    {
        $this->info('Make sure to add this to the following files:');

        $this->line('');

        $this->comment('App\Providers\AppServiceProvider.php');
        $this->comment('====================================');

        $this->line('public function boot()');
        $this->line('{');
        $this->line('   AliasLoader::getInstance([');

        if ($this->all() || $this->option('repository'))
            $this->line("       '{$model}Repository' => {$model}Repository::class,");

        $this->line("       '{$model}'           => {$model}::class,");
        $this->line('   ]);');
        $this->line('}');

        $this->line('');

        $this->line('public function register()');
        $this->line('{');
        $this->line("   \$this->app->bind('App\\Contracts\\$model', 'App\\Models\\$model');");

        if ($this->all() || $this->option('repository'))
            $this->line("   \$this->app->bind('App\\Contracts\\{$model}Repository', 'App\\Repositories\\{$model}Repository');");

        $this->line("   \$this->app->bind('$model', 'App\\Contracts\\$model');");

        if ($this->all() || $this->option('repository'))
            $this->line("   \$this->app->bind('{$model}Repository', 'App\\Contracts\\{$model}Repository');");
        $this->line('}');

        $this->line('');

        $this->comment('App\Http\routes.php');
        $this->comment('===================');

        if ($this->all() || $this->option('controller'))
            $this->line("Route::resource('$str_to_lower_model', '{$model}Controller');");

        $this->line('');

        if ($this->all() || $this->option('controller'))
        {
            $this->comment('App\Providers\RouteServiceProvider.php');
            $this->comment('==================================');

            $this->line('public function boot()');
            $this->line('{');
            $this->line("   \$router->bind('$str_to_lower_model', function (\$value)");
            $this->line("   {");
            $this->line("       \$model = app('$model');");
            $this->line("");
            $this->line("       if (in_array('SoftDeletes', class_uses(\$model)))");
            $this->line("           return \$model->withTrashed()");
            $this->line("                         ->find(\$value);");
            $this->line("");
            $this->line("       return \$model->find(\$value);");
            $this->line("   )});");
            $this->line('');
            $this->line('   parent::boot($router);');
            $this->line('}');
            $this->line('');
        }

        $this->info('Resource Complete!');
    }

    /**
     * @param $stub
     *
     * @return array|bool|string
     */
    private function getOption($stub)
    {
        if (str_contains($stub, 'Repository'))
            return $this->option('repository');

        if (str_contains($stub, 'Controller'))
            return $this->option('controller');

        return true;
    }

    /**
     * @param $str_to_lower_model
     */
    private function makeMigration($str_to_lower_model)
    {
        // migration
        if ($this->option('migration') || $this->all())
        {
            $contents = $this->replaceDummy(File::get(__DIR__ . '/stubs/Migration.php'));

            File::put(database_path('migrations/' . date('Y_m_d_His_') . 'create_' . str_plural($str_to_lower_model) . '_table.php'), $contents);

            $this->info('Migration Created!');
        }
    }

    /**
     * @param $model
     */
    private function makeSeeder($model)
    {
        // seeder
        if ($this->option('seeder') || $this->all())
            \Artisan::call('make:seeder', [ 'name' => $model . 'TableSeeder' ]);
    }

    /**
     * @param $model
     */
    private function makeCreateRequest($model)
    {
        // create request
        if ($this->option('create-request') || $this->all())
        {
            \Artisan::call('make:request', [ 'name' => 'Create' . $model ]);
            $this->info('CreateRequest created!');
        }
    }

    /**
     * @param $model
     */
    private function makeUpdateRequest($model)
    {
        // update request
        if ($this->option('update-request') || $this->all())
        {
            \Artisan::call('make:request', [ 'name' => 'Update' . $model ]);

            $this->info('UpdateRequest created!');
        }
    }

    /**
     * @param $stub
     *
     * @return mixed|string
     */
    private function buildContents($stub)
    {
        $package = __DIR__ . '/stubs/' . $stub . '.php';
        
        $custom = base_path('resources/stubs/vendor/resource-generator/' . $stub . '.php');

        $contents = File::exists($custom) ? File::get($custom) : File::get($package);

        return $this->replaceDummy($contents);
    }

    /**
     * @param $stub
     * @param $filename
     */
    private function storeFile($stub, $filename)
    {
        File::put($filename, $this->buildContents($stub));

        $this->info($stub . ' created!');
    }

    /**
     * @param $stub
     * @param $directory
     */
    private function handleFile($stub, $directory)
    {
        $filename = $this->makeFileName($stub, $directory);

        File::exists($filename) ? $this->warn($filename . ' already exists skipping') : $this->storeFile($stub, $filename);
    }

    /**
     * @param $directory
     */
    private function checkDirectory($directory)
    {
        File::isDirectory(app_path($directory)) ?: File::makeDirectory(app_path($directory), '0777', true, true);
    }

    /**
     * @param $stub
     * @param $directory
     *
     * @return string
     */
    private function makeFileName($stub, $directory)
    {
        return app_path($directory . '/' . $this->makeClassName($stub) . '.php');
    }

    /**
     * @param $contents
     *
     * @return mixed
     */
    private function replaceDummy($contents)
    {
        return str_replace([ 'Dummy', 'dummy', 'Dummies', 'dummies' ], [
            studly_case($this->resource),
            snake_case($this->resource),
            str_plural(studly_case($this->resource)),
            str_plural(snake_case($this->resource))
        ], $contents);
    }

    /**
     * @param Composer $composer
     */
    private function askToMigrate(Composer $composer)
    {
        if ($this->confirm('Would you like to migrate the database now?'))
        {
            $composer->dumpAutoloads();

            \Artisan::call('migrate');
        }
    }

    /**
     * @param Composer $composer
     * @param $model
     */
    private function askToSeed(Composer $composer, $model)
    {
        if ($this->confirm('Would you like to seed the database now?'))
        {
            $composer->dumpAutoloads();

            \Artisan::call('db:seed', [ 'class' => $model . 'TableSeeder' ]);
        }
    }
}
