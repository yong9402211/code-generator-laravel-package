<?php

namespace App\Services;

use App\Models\EmployerUser;
use App\Traits\ServiceTrait;

class EmployerUserService
{
    use ServiceTrait;

    protected $model;

    public function __construct(EmployerUser $model)
    {
        $this->model = $model;
    }
}
