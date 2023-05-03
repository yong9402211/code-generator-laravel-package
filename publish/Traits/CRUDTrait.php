<?php

namespace App\Traits;

use App\Http\Resources\SuccessResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/*
    This trait is use for standard crud
    This trait required:
        - $service
*/

trait CRUDTrait
{
    public function index(): JsonResource
    {
        $models = $this->service->getAll();

        return new SuccessResource($models);
    }

    public function show($uuid): JsonResource
    {
        $model = $this->service->getById($uuid);

        return new SuccessResource($model);
    }

    public function store(Request $request): JsonResource
    {
        $models = $this->service->create($request->only($this->model::getStorable()));

        return new SuccessResource($models);
    }

    public function update(Request $request, $id): JsonResource
    {
        $model = $this->service->updateById($request->only($this->model::getUpdatable()), $id);

        return new SuccessResource($model);
    }

    public function delete($id): JsonResource
    {
        $model = $this->service->deleteWithId($id);

        return new SuccessResource($model);
    }
}
