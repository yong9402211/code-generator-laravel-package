<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ServiceTrait
{
    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);

        return $model;
    }

    public function updateById(array $data, $id)
    {
        $model = $this->model->where('id', $id)->firstOrFail();
        $model->update($data);

        return $model;
    }

    public function updateWithId(array $data, $id): int
    {
        $result = $this->model->where('id', $id)->update($data);

        return $result;
    }

    public function deleteById($id)
    {
        $model = $this->model->where('id', $id)->firstOrFail();
        $model->delete();

        return $model;
    }

    public function deleteWithId($id): int
    {
        $model = $this->model->where('id', $id)->delete();
        return $model;
    }
}
