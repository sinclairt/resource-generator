<?php

namespace App\Repositories;

use App\Contracts\Dummy;
use App\Contracts\DummyRepository as DummyRepositoryInterface;

class DummyRepository extends Repository implements DummyRepositoryInterface
{
    public $model;

    public function __construct(Dummy $model)
    {
        $this->model = $model;
    }
}