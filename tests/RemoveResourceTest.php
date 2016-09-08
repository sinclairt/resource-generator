<?php

require_once 'DbTestCase.php';

/**
 * Class CreateResourceTest
 */
class RemoveResourceTest extends DbTestCase
{
    public function test_i_can_remove_a_resource_without_any_flags()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true ]);

        $this->checkModelFilesDoNotExist();
        $this->checkRepositoryFilesDoNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkMigrationDoesNotExist();
        $this->checkSeederDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

    public function test_i_can_remove_a_resource_with_the_all_flag()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--all' => true ]);

        $this->checkModelFilesDoNotExist();
        $this->checkRepositoryFilesDoNotExist();
        $this->checkControllerDoesNotExist();
        $this->checkApiControllerDoesNotExist();
        $this->checkMigrationDoesNotExist();
        $this->checkSeederDoesNotExist();
        $this->checkCreateRequestDoesNotExist();
        $this->checkUpdateRequestDoesNotExist();
    }

    public function test_i_can_remove_a_model()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--model' => true ]);

        $this->checkModelFilesDoNotExist();

        $this->checkMigrationExists();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkSeederExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_remove_a_migration()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--migration' => true ]);

        $this->checkMigrationDoesNotExist();

        $this->checkModelFilesExist();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkSeederExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_remove_a_seeder()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--seeder' => true ]);

        $this->checkSeederDoesNotExist();

        $this->checkModelFilesExist();
        $this->checkMigrationExists();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_remove_a_create_request()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--create-request' => true ]);

        $this->checkCreateRequestDoesNotExist();

        $this->checkModelFilesExist();
        $this->checkSeederExists();
        $this->checkMigrationExists();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_remove_an_update_request()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--update-request' => true ]);

        $this->checkUpdateRequestDoesNotExist();

        $this->checkModelFilesExist();
        $this->checkSeederExists();
        $this->checkMigrationExists();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkCreateRequestExists();
    }

    public function test_i_can_remove_a_repository()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--repository' => true ]);

        $this->checkRepositoryFilesDoNotExist();

        $this->checkModelFilesExist();
        $this->checkSeederExists();
        $this->checkMigrationExists();
        $this->checkControllerExists();
        $this->checkApiControllerExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_remove_a_controller()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--controller' => true ]);

        $this->checkControllerDoesNotExist();

        $this->checkModelFilesExist();
        $this->checkSeederExists();
        $this->checkMigrationExists();
        $this->checkRepositoryFilesExist();
        $this->checkApiControllerExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

    public function test_i_can_remove_an_api_controller()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true, '--api-controller' => true ]);

        $this->checkApiControllerDoesNotExist();

        $this->checkModelFilesExist();
        $this->checkSeederExists();
        $this->checkMigrationExists();
        $this->checkRepositoryFilesExist();
        $this->checkControllerExists();
        $this->checkCreateRequestExists();
        $this->checkUpdateRequestExists();
    }

}