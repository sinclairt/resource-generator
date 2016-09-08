<?php

class TestCustomDirectories extends CustomDirectoriesDbTestCase
{
    public function test_i_can_create_a_resource_using_a_custom_directory()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        $this->assertTrue(is_dir(__DIR__ . '/../vendor/laravel/laravel/app/Foo'));

        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Models/Foo.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Contracts/Foo.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Facades/Foo.php');

        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Repositories/FooRepository.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Contracts/FooRepository.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Facades/FooRepository.php');

        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers/FooController.php');

        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers/Api/FooController.php');

        $this->checkMigrationExists();

        $this->checkCreateRequestExists();

        $this->checkUpdateRequestExists();

        $this->checkSeederExists();
    }

    public function test_i_can_remove_a_resource_using_a_custom_directory()
    {
        \Artisan::call('resource:create', [ 'resource' => 'foo', '--no-interaction' => true ]);

        \Artisan::call('resource:remove', [ 'resource' => 'foo', '--no-interaction' => true ]);

        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Models/Foo.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Contracts/Foo.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Facades/Foo.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Repositories/FooRepository.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Contracts/FooRepository.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Facades/FooRepository.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers/FooController.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers/Api/FooController.php');

        $this->checkMigrationDoesNotExist();

        $this->checkCreateRequestDoesNotExist();

        $this->checkUpdateRequestDoesNotExist();

        $this->checkSeederDoesNotExist();
    }
}