<?php

namespace App\Http\Controllers;

use App\Contracts\DummyRepository;
use App\Http\Requests;
use App\Http\Requests\CreateDummy;
use App\Http\Requests\UpdateDummy;
use Sinclair\CrudController\Traits\CrudController;

/**
 * Class DummyController
 * @package App\Http\Controllers
 */
class DummyController extends Controller
{
    use CrudController;

    /**
     * @param DummyRepository $repository
     */
    public function __construct(DummyRepository $repository)
    {
        $this->setUp($repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateDummy $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDummy $request)
    {
        return $this->doStore($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDummy $request
     * @param $model
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDummy $request, $model)
    {
        return $this->doUpdate($request, $model);
    }
}
