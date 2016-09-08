<?php

use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;

/**
 * Class DbTestCase
 */
abstract class DbTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * @var mixed
     */
    protected $baseUrl;

    /**
     * DbTestCase constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->baseUrl = env('APP_URL');
    }

    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register(\Sinclair\ResourceGenerator\ResourceGeneratorServiceProvider::class);

        $app->make('Illuminate\Contracts\Console\Kernel')
            ->bootstrap();

        \Artisan::call('vendor:publish', [ '--provider' => \Sinclair\ResourceGenerator\ResourceGeneratorServiceProvider::class, '--tag' => 'config' ]);

        $app->make('Illuminate\Foundation\Bootstrap\LoadConfiguration')->bootstrap($app);

        return $app;
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->app[ 'config' ]->set('database.default', 'sqlite');
        $this->app[ 'config' ]->set('database.connections.sqlite.database', ':memory:');

//        $this->migrate();
    }

    /**
     * run package database migrations
     *
     * @param string $path
     */
    public function migrate( $path = __DIR__ . "/../src/migrations" )
    {
        $fileSystem = new Filesystem;
        $classFinder = new ClassFinder;

        foreach ( $fileSystem->files($path) as $file )
        {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);

            ( new $migrationClass )->up();
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        $files = [
            __DIR__ . '/../vendor/laravel/laravel/app/Models/Foo.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Contracts/Foo.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Facades/Foo.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Repositories/FooRepository.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Contracts/FooRepository.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Facades/FooRepository.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/FooController.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/Api/FooController.php',
            __DIR__ . '/../vendor/laravel/laravel/database/seeds/FooTableSeeder.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Http/Requests/CreateFoo.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Http/Requests/UpdateFoo.php',

            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Models/Foo.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Contracts/Foo.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Facades/Foo.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Repositories/FooRepository.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Contracts/FooRepository.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Facades/FooRepository.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers/FooController.php',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers/Api/FooController.php',

            __DIR__ . '/../vendor/laravel/laravel/config/resource-generator.php',

            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/ApiController.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/Controller.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/Migration.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/Model.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/ModelFacade.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/ModelInterface.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/Repository.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/RepositoryFacade.php',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator/RepositoryInterface.php',
        ];

        array_map('unlink', array_filter($files, 'file_exists'));

        array_map('unlink', glob(__DIR__ . '/../vendor/laravel/laravel/database/migrations/*_create_foos_table.php'));

        $directories = [
            __DIR__ . '/../vendor/laravel/laravel/app/Models',
            __DIR__ . '/../vendor/laravel/laravel/app/Contracts',
            __DIR__ . '/../vendor/laravel/laravel/app/Facades',
            __DIR__ . '/../vendor/laravel/laravel/app/Repositories',
            __DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/Api',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor/resource-generator',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs/vendor',
            __DIR__ . '/../vendor/laravel/laravel/resources/stubs',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Contracts',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Facades',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers/Api',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Http/Controllers',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Http',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Models',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo/Repositories',
            __DIR__ . '/../vendor/laravel/laravel/app/Foo',
        ];

        array_map('rmdir', array_filter($directories, 'is_dir'));
    }

    protected function checkModelFilesExist()
    {
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Models/Foo.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Contracts/Foo.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Facades/Foo.php');
    }

    protected function checkRepositoryFilesExist()
    {
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Repositories/FooRepository.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Contracts/FooRepository.php');
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Facades/FooRepository.php');
    }

    protected function checkControllerExists()
    {
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/FooController.php');
    }

    protected function checkApiControllerExists()
    {
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/Api/FooController.php');
    }

    protected function checkMigrationExists()
    {
        $this->assertFileExists(head(glob(__DIR__ . '/../vendor/laravel/laravel/database/migrations/*_create_foos_table.php')));
    }

    protected function checkSeederExists()
    {
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/database/seeds/FooTableSeeder.php');
    }

    protected function checkCreateRequestExists()
    {
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Requests/CreateFoo.php');
    }

    protected function checkUpdateRequestExists()
    {
        $this->assertFileExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Requests/UpdateFoo.php');
    }

    protected function checkModelFilesDoNotExist()
    {
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Models/Foo.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Contracts/Foo.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Facades/Foo.php');
    }

    protected function checkRepositoryFilesDoNotExist()
    {
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Repositories/FooRepository.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Contracts/FooRepository.php');
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Facades/FooRepository.php');
    }

    protected function checkControllerDoesNotExist()
    {
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/FooController.php');
    }

    protected function checkApiControllerDoesNotExist()
    {
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Controllers/Api/FooController.php');
    }

    protected function checkMigrationDoesNotExist()
    {
        $this->assertEquals(0, sizeof(glob(__DIR__ . '/../vendor/laravel/laravel/database/migrations/*_create_foos_table.php')));
    }

    protected function checkSeederDoesNotExist()
    {
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/database/seeds/FooTableSeeder.php');
    }

    protected function checkCreateRequestDoesNotExist()
    {
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Requests/CreateFoo.php');
    }

    protected function checkUpdateRequestDoesNotExist()
    {
        $this->assertFileNotExists(__DIR__ . '/../vendor/laravel/laravel/app/Http/Requests/UpdateFoo.php');
    }
}