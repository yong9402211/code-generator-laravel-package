<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployerUserRequest;
use App\Http\Requests\UpdateEmployerUserRequest;
use App\Services\EmployerUserService;
use App\Traits\CRUDTrait;

class EmployerUserController extends Controller
{
    use CRUDTrait;

    protected $service;

    public function __construct(EmployerUserService $service)
    {
        $this->service = $service;
    }

    public function store(StoreEmployerUserRequest $request)
    {
        return parent::store($request);
    }

    public function update(UpdateEmployerUserRequest $request, $uuid)
    {
        return parent::update($request, $uuid);
    }
}
