<?php

namespace App\Repositories;

use App\Contracts\Dummy;
use App\Contracts\DummyRepository as DummyRepositoryInterface;
use Sinclair\Repository\Repositories\Repository;

/**
 * Class DummyRepository
 * @package App\Repositories
 */
class DummyRepository extends Repository implements DummyRepositoryInterface
{
    /**
     * @var Dummy
     */
    public $model;

    /**
     * DummyRepository constructor.
     *
     * @param Dummy $model
     */
    public function __construct( Dummy $model )
    {
        $this->model = $model;
    }
}