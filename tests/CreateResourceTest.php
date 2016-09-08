<?php

require_once 'DbTestCase.php';

/**
 * Class CreateResourceTest
 */
class CreateResourceTest extends DbTestCase
{
    public function test_i_can_create_a_resource_without_any_flags()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        $this->checkModelFilesExist();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkMigrationExists();
        $this->checkSeederExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_create_a_resource_with_the_all_flag()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--all' => true ]);

        $this->checkModelFilesExist();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkMigrationExists();
        $this->checkSeederExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_can_create_a_resource_when_the_folders_already_exist()
    {
        $directories = [
            __DIR__ . '/../vendor/laravel/laravel/app/Models',
            __DIR__ . '/../vendor/laravel/laravel/app/Contracts',
            __DIR__ . '/../vendor/laravel/laravel/app/Facades',
            __DIR__ . '/../vendor/laravel/laravel/app/Repositories',
            __DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/Api',
        ];

        array_map('mkdir', array_diff($directories, array_filter($directories, 'is_dir')));

        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        $this->checkModelFilesExist();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkMigrationExists();
        $this->checkSeederExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_create_only_a_model_and_migration()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--migration' => true ]);

        $this->checkModelFilesExist();
        $this->checkMigrationExists();

        $this->checkRepositoryFilesDoNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkSeederDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

    public function test_i_can_create_only_a_model_and_a_seeder()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--seeder' => true ]);

        $this->checkModelFilesExist();
        $this->checkSeederExists();

        $this->checkMigrationDoesNotExist();
        $this->checkRepositoryFilesDoNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

    public function test_i_can_create_only_a_model_and_a_create_request()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--create-request' => true ]);

        $this->checkModelFilesExist();
        $this->checkCreateRequestExists();

        $this->checkSeederDoesNotExist();
        $this->checkMigrationDoesNotExist();
        $this->checkRepositoryFilesDoNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

    public function test_i_can_create_only_a_model_and_an_update_request()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--update-request' => true ]);

        $this->checkModelFilesExist();
        $this->checkUpdateRequestExists();

        $this->checkSeederDoesNotExist();
        $this->checkMigrationDoesNotExist();
        $this->checkRepositoryFilesDoNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
    }

    public function test_i_can_create_only_a_model_and_a_repository()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--repository' => true ]);

        $this->checkModelFilesExist();
        $this->checkRepositoryFilesExist();

        $this->checkSeederDoesNotExist();
        $this->checkMigrationDoesNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

    public function test_i_can_create_a_model_and_a_controller()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--controller' => true ]);

        $this->checkModelFilesExist();
        $this->checkControllerExists();

        $this->checkSeederDoesNotExist();
        $this->checkMigrationDoesNotExist();
        $this->checkRepositoryFilesDoNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

    public function test_i_can_create_a_model_and_an_api_controller()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true, '--api-controller' => true ]);

        $this->checkModelFilesExist();
        $this->checkApiControllerExists();

        $this->checkSeederDoesNotExist();
        $this->checkMigrationDoesNotExist();
        $this->checkRepositoryFilesDoNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

}