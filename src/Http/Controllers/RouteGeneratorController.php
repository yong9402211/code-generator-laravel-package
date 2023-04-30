<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;

class RouteGeneratorController extends Controller
{
    use GenerateTrait;

    protected $generateType = 'route';

    protected $stubFileName = 'route.stub';

    protected $routeList = [];

    protected $routePrefix = '';

    protected $variables = [
        'useController', 'routePrefix', 'routes',
    ];

    public function generate()
    {
        $routes = $this->standardRoute();
        $routes = array_merge($routes, $this->setting['routes']);

        $this->routePrefix = Str::kebab($this->getPluralStudyName());

        foreach ($routes as $route => $routeInfo) {
            $method = $routeInfo['method'] ?? 'get';
            $routeName = $routeInfo['route'] ?? null;

            $controller = $this->getControllerName();
            $controllerMethod = $this->getControllerMethodName($route);

            if (is_null($routeName)) {
                $routeName = $route;
            }

            $code = "Route::{$method}('$routeName', [{$controller}::class, '{$controllerMethod}']);";
            $this->routeList[] = $code;
        }

        $this->create();
    }

    protected function getControllerName()
    {
        return $this->getSingularStudyName() . 'Controller';
    }

    protected function standardRoute()
    {
        $routes = [
            'index' => [
                'route' => '',
            ],
            'store' => [
                'route' => '',
                'method' => 'post',
            ],
            'update' => [
                'route' => '{uuid}',
                'method' => 'put',
            ],
            'show' => [
                'route' => '{uuid}',
            ],
            'delete' => [
                'route' => '{uuid}',
                'method' => 'delete',
            ],
        ];

        return $routes;


        // Route::get('', [EmployerUserController::class, 'index']);
        // Route::post('', [EmployerUserController::class, 'store']);
        // Route::put('{uuid}', [EmployerUserController::class, 'update']);
        // Route::get('{uuid}', [EmployerUserController::class, 'show']);
        // Route::delete('{uuid}', [EmployerUserController::class, 'delete']);
    }

    protected function getControllerMethodName($route)
    {
        if (strpos($route, '/') !== false) {
            $route = explode('/', $route)[0];
        }

        return Str::camel($route);
    }

    protected function setClassName($name, $generateType)
    {
        $this->className = $name;
    }

    protected function getUseController()
    {
        return 'App\Http\Controllers' . $this->getControllerName();
    }

    protected function getRoutePrefix()
    {
        return $this->routePrefix;
    }

    protected function getRoutes()
    {
        $columnStr = implode("\n", $this->routeList);
        return $columnStr;
    }
}
