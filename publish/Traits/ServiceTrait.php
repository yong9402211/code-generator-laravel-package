<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ServiceTrait
{
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById($id): Collection
    {
        return $this->model->find($id);
    }

    public function getByUuid($id): Collection
    {
        return $this->model->where('uuid', $id)->first();
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);

        return $model;
    }

    public function updateByUuid(array $data, $uuid)
    {
        $model = $this->model->where('uuid', $uuid)->firstOrFail();
        $model->update($data);

        return $model;
    }

    public function updateWithUuid(array $data, $uuid): int
    {
        $result = $this->model->where('uuid', $uuid)->update($data);

        return $result;
    }

    public function deleteByUuid($uuid): Collection
    {
        $model = $this->model->where('uuid', $uuid)->firstOrFail();
        $model->delete();

        return $model;
    }

    public function deleteWithUuid($uuid): int
    {
        $model = $this->model->where('uuid', $uuid)->delete();
        return $model;
    }
}
