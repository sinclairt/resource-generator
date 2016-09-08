<?php

namespace Sinclair\ResourceGenerator;

use File;
use Illuminate\Console\Command;

/**
 * Class RemoveResource
 * @package Sinclair\ResourceGenerator
 */
class RemoveResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:remove {resource : The name of the resource you want to remove}
                            {--all : Remove all resource files}
                            {--migration : Only remove the migration}
                            {--seeder : Only remove the seeder}
                            {--create-request : Only remove the create request}
                            {--update-request : Only remove the update request}
                            {--repository : Only remove the repository}
                            {--controller : Only remove the controller}
                            {--api-controller : Only remove the api controller}
                            {--model : Only remove the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the classes and interfaces associated with the resource';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = studly_case($this->argument('resource'));

        $repository = $model . 'Repository';

        $files = $this->setFiles($model, $repository);

        if ( $this->notAll() )
            $this->checkOptions($files, $model, $repository);

        $this->removeFiles($files, $model);
    }

    /**
     * @param $model
     * @param $repository
     *
     * @return array
     */
    private function setFiles( $model, $repository )
    {
        return [
            config('resource-generator.objects-map.model', 'Models') . '/' . $model,
            config('resource-generator.objects-map.model-interface', 'Contracts') . '/' . $model,
            config('resource-generator.objects-map.model-facade', 'Facades') . '/' . $model,
            config('resource-generator.objects-map.repository', 'Repositories') . '/' . $repository,
            config('resource-generator.objects-map.repository-interface', 'Contracts') . '/' . $repository,
            config('resource-generator.objects-map.repository-facade', 'Facades') . '/' . $repository,
            config('resource-generator.objects-map.controller', 'Http/Controllers') . '/' . $model . 'Controller',
            config('resource-generator.objects-map.api-controller', 'Http/Controllers/Api') . '/' . $model . 'Controller',
            'Http/Requests/' . 'Create' . $model,
            'Http/Requests/' . 'Update' . $model,
        ];
    }

    /**
     * @param $files
     * @param $model
     */
    private function keepModelClasses( &$files, $model )
    {
        unset( $files[ array_search(config('resource-generator.objects-map.model', 'Models') . '/' . $model, $files) ] );
        unset( $files[ array_search(config('resource-generator.objects-map.model-facade', 'Facades') . '/' . $model, $files) ] );
        unset( $files[ array_search(config('resource-generator.objects-map.model-interface', 'Contracts') . '/' . $model, $files) ] );
    }

    /**
     * @param $files
     * @param $repository
     */
    private function keepRepositoryClasses( &$files, $repository )
    {
        unset( $files[ array_search(config('resource-generator.objects-map.repository', 'Repositories') . '/' . $repository, $files) ] );
        unset( $files[ array_search(config('resource-generator.objects-map.repository-facade', 'Facades') . '/' . $repository, $files) ] );
        unset( $files[ array_search(config('resource-generator.objects-map.repository-interface', 'Contracts') . '/' . $repository, $files) ] );
    }

    /**
     * @param $files
     * @param $model
     * @param $repository
     */
    private function checkOptions( &$files, $model, $repository )
    {
        if ( $this->option('create-request') == null )
            unset( $files[ array_search('Http/Requests/' . 'Create' . $model, $files) ] );

        if ( $this->option('update-request') == null )
            unset( $files[ array_search('Http/Requests/' . 'Update' . $model, $files) ] );

        if ( $this->option('repository') == null )
            $this->keepRepositoryClasses($files, $repository);

        if ( $this->option('model') == null )
            $this->keepModelClasses($files, $model);

        if ( $this->option('controller') == null )
            unset( $files[ array_search(config('resource-generator.objects-map.controller', 'Http/Controllers') . '/' . $model . 'Controller', $files) ] );

        if ( $this->option('api-controller') == null )
            unset( $files[ array_search(config('resource-generator.objects-map.api-controller', 'Http/Controllers/Api') . '/' . $model . 'Controller', $files) ] );
    }

    /**
     * @param $file
     */
    private function handleFileDeletion( $file )
    {
        if ( File::exists(app_path($file . '.php')) )
        {
            File::delete(app_path($file . '.php'));

            $this->info($file . ' Deleted!');
        }
    }

    /**
     * @param $model
     */
    private function handleSeederRemoval( $model )
    {
        if ( File::exists(database_path('seeds/' . $model . 'TableSeeder.php')) )
        {
            File::delete(database_path('seeds/' . $model . 'TableSeeder.php'));

            $this->info($model . 'TableSeeder Deleted!');
        }
    }

    /**
     * @param $model
     */
    private function handleMigrationDeletion( $model )
    {
        $migrations = File::glob(database_path('migrations/*_create_' . str_plural(strtolower($model)) . '_table.php'));

        sizeof($migrations) > 1 ?
            $this->warn('Not removing migration as there is more than 1 file that matches the pattern') :
            $this->deleteMigration(head($migrations));
    }

    /**
     * @param $files
     * @param $model
     */
    private function removeFiles( $files, $model )
    {
        foreach ( $files as $file )
            $this->handleFileDeletion($file);

        // remove table seeder
        if ( $this->all() || $this->option('seeder') )
            $this->handleSeederRemoval($model);

        // remove migration
        if ( $this->all() || $this->option('migration') )
            $this->handleMigrationDeletion($model);
    }

    /**
     * @return bool
     */
    private function notAll()
    {
        $options = $this->option();

        unset( $options[ 'no-interaction' ] );

        return $this->option('all') == false && sizeof(array_filter($options)) > 0;
    }

    /**
     * @param $migration
     *
     * @return bool
     *
     */
    private function deleteMigration( $migration )
    {
        if ( File::exists($migration) )
        {
            File::delete($migration);

            $this->info($migration . ' Deleted!');
        }
    }

    /**
     * @return bool
     */
    private function all()
    {
        return !$this->notAll();
    }
}
