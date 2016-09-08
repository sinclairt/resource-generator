<?php

use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;

/**
 * Class DbTestCase
 */
abstract class CustomDirectoriesDbTestCase extends DbTestCase
{
    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        // we need to copy the config file before bootstrapping the application
        if ( file_exists(__DIR__ . '/../vendor/laravel/laravel/config/resource-generator.php'))
            unlink(__DIR__ . '/../vendor/laravel/laravel/config/resource-generator.php');

        copy(__DIR__ . '/config/resource-generator.php', __DIR__ . '/../vendor/laravel/laravel/config/resource-generator.php');

        return parent::createApplication();
    }
}