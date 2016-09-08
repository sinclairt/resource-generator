<?php

namespace App\Http\Controllers\Api;

use App\Contracts\DummyRepository;
use App\Http\Controllers\Controller;
use League\Fractal\TransformerAbstract;
use Sinclair\ApiFoundation\Traits\ApiFoundation;
use Sinclair\ApiFoundation\Transformers\DefaultTransformer;
use Sinclair\Repository\Contracts\Repository;

class DummyController extends Controller
{
    use ApiFoundation;

    /**
     * ApiFoundation constructor.
     *
     * @param DummyRepository|Repository $repository
     * @param TransformerAbstract|DefaultTransformer $transformer
     * @param null $resourceName
     */
    public function __construct( DummyRepository $repository, DefaultTransformer $transformer, $resourceName = null )
    {
        $this->repository = $repository;

        $this->transformer = $transformer;

        $this->resourceName = $resourceName;
    }
}