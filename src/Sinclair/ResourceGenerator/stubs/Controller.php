<?php

namespace App\Http\Controllers;

use App\Contracts\DummyRepository;
use App\Http\Requests;
use App\Http\Requests\CreateDummy;
use App\Http\Requests\UpdateDummy;
use Sinclair\CrudController\Traits\CrudController;

class DummyController
{
    use CrudController;

    /**
     * @var DummyRepository
     */
    protected $repository;

    /**
     * @param DummyRepository $repository
     */
    public function __construct(DummyRepository $repository)
    {
        $this->repository = $repository;
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
        $this->repository->add($request->all());

        return redirect()->route('dummy.index');

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
        $this->repository->update($request->all(), $model);

        return redirect()->route('dummy.index');
    }
}
