# README #

Create and remove components of a resource. These include:
* Model
* Model Interface
* Model Facade
* Repository
* Repository Interface
* Repository Facade
* Resourceful Controller
* Migration
* Seeder
* Create Form Request
* Update From Request

### How do I get set up? ###

* ``` composer require sterling\resource-generator ```.
* Register the service provider ``` Sterling\ResourceGenerator\ResourceGeneratorServiceProvider ``` in ``` app\config ```.
* Run ``` php artisan ``` to see two new commands ``` resource:create ``` and ``` resource:remove ```.

### Usage ###

Both commands require the name argument i.e. ``` php artisan resource:create foo ```.

You can pass in optional flags to only create/remove particular elements:

- ``` --model ``` (remove only)
- ``` --repository ```
- ``` --controller ```
- ``` --create-request ```
- ``` --update-request ```
- ``` --migration ```
- ``` --seeder ```
- ``` --all ```

### Customise ###
Run ``` php artisan vendor:publish ``` to publish the file stubs to ``` resources/stubs/vendor/resource-generator ```. Here you can edit the stubs to make your generation easier.