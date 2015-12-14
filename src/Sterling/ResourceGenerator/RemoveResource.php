<?php

namespace Sterling\ResourceGenerator;

use File;
use Illuminate\Console\Command;

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
                            {--model : Only remove the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the classes and interfaces associated with the resource';

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
     * @return mixed
     */
    public function handle()
    {
        $model = ucwords($this->argument('resource'));

        $repository = $model . 'Repository';

        $files = $this->setFiles($model, $repository);

        if ($this->notAll())
            $this->checkOptions($files, $model, $repository);

        $this->removeFiles($files, $model);
    }

    /**
     * @param $model
     * @param $repository
     *
     * @return array
     */
    private function setFiles($model, $repository)
    {
        return [
            'Models/' . $model,
            'Contracts/' . $model,
            'Facades/' . $model,
            'Repositories/' . $repository,
            'Contracts/' . $repository,
            'Facades/' . $repository,
            'Http/Requests/' . 'Create' . $model,
            'Http/Requests/' . 'Update' . $model,
            'Http/Controllers/' . $model . 'Controller'
        ];
    }

    /**
     * @param $files
     * @param $model
     */
    private function keepModelClasses(&$files, $model)
    {
        unset($files[ array_search('Models/' . $model, $files) ]);
        unset($files[ array_search('Facades/' . $model, $files) ]);
        unset($files[ array_search('Contracts/' . $model, $files) ]);
    }

    /**
     * @param $files
     * @param $repository
     */
    private function keepRepositoryClasses(&$files, $repository)
    {
        unset($files[ array_search('Repositories/' . $repository, $files) ]);
        unset($files[ array_search('Facades/' . $repository, $files) ]);
        unset($files[ array_search('Contracts/' . $repository, $files) ]);
    }

    /**
     * @param $files
     * @param $model
     * @param $repository
     */
    private function checkOptions(&$files, $model, $repository)
    {
        if ($this->option('create-request') == null)
            unset($files[ array_search('Http/Requests/' . 'Create' . $model, $files) ]);

        if ($this->option('update-request') == null)
            unset($files[ array_search('Http/Requests/' . 'Update' . $model, $files) ]);

        if ($this->option('repository') == null)
            $this->keepRepositoryClasses($files, $repository);

        if ($this->option('model') == null)
            $this->keepModelClasses($files, $model);

        if ($this->option('controller') == null)
            unset($files[ array_search('Http/Controllers/' . $model . 'Controller', $files) ]);
    }

    /**
     * @param $file
     */
    private function handleFileDeletion($file)
    {
        if (File::exists(app_path($file . '.php')))
        {
            File::delete(app_path($file . '.php'));

            $this->info($file . ' Deleted!');
        }
    }

    /**
     * @param $model
     */
    private function handleSeederRemoval($model)
    {
        if (File::exists(database_path('seeds\\' . $model . 'TableSeeder.php')))
        {
            File::delete(database_path('seeds\\' . $model . 'TableSeeder.php'));

            $this->info($model . 'TableSeeder Deleted!');
        }
    }

    /**
     * @param $model
     */
    private function handleMigrationDeletion($model)
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
    private function removeFiles($files, $model)
    {
        foreach ($files as $file)
            $this->handleFileDeletion($file);

        // remove table seeder
        if (! $this->notAll() || $this->option('seeder'))
            $this->handleSeederRemoval($model);

        // remove migration
        if (! $this->notAll() || $this->option('migration'))
            $this->handleMigrationDeletion($model);
    }

    /**
     * @return bool
     */
    private function notAll()
    {
        return $this->option('all') == false && sizeof(array_filter($this->option())) > 0;
    }

    /**
     * @param $migration
     *
     * @return bool
     *
     */
    private function deleteMigration($migration)
    {
        if (File::exists($migration))
        {
            File::delete($migration);

            $this->info($migration . ' Deleted!');
        }
    }
}
