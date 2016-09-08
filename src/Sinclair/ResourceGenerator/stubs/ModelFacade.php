<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Dummy
 * @package App\Facades
 */
class Dummy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Dummy';
    }
}