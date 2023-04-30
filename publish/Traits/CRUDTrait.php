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
        $model = $this->service->getByUuid($uuid);

        return new SuccessResource($model);
    }

    public function store(Request $request): JsonResource
    {
        $models = $this->service->create($request->all());

        return new SuccessResource($models);
    }

    public function update(Request $request, $uuid): JsonResource
    {
        $model = $this->service->updateByUuid($request->all(), $uuid);

        return new SuccessResource($model);
    }

    public function delete($uuid): JsonResource
    {
        $model = $this->service->deleteWithUuid($uuid);

        return new SuccessResource($model);
    }
}
