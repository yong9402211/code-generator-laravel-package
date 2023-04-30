<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;
use Yjh94\StandardCodeGenerator\Utils\Indent;

class RouteGeneratorController extends Controller
{
    use GenerateTrait;

    protected $generateType = 'route';

    protected $stubFileName = 'route.stub';

    protected $routeList = [];

    protected $routePrefix = '';

    protected $variables = [
        'use', 'routePrefix', 'routes',
    ];

    public function generate()
    {
        $routes = $this->standardRoute();
        $routes = array_merge($routes, $this->setting['routes']);

        $this->routePrefix = Str::kebab($this->getPluralStudyName());
        $indent = Indent::make(4);

        foreach ($routes as $route => $routeInfo) {
            $method = $routeInfo['method'] ?? 'get';
            $routeName = $routeInfo['route'] ?? null;

            $controller = $this->getControllerName();
            $controllerMethod = $this->getControllerMethodName($route);

            if (is_null($routeName)) {
                $routeName = $route;
            }

            $code = "Route::{$method}('$routeName', [{$controller}::class, '{$controllerMethod}']);";
            $this->routeList[] = $indent . $code;
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
    }

    protected function getControllerMethodName($route)
    {
        if (strpos($route, '/') !== false) {
            $route = explode('/', $route)[0];
        }

        return Str::camel($route);
    }

    public function getUse()
    {
        $useStr = '';
        $useList = ['Controller'];
        foreach ($useList as $name) {
            $namespace = 'get' . $name . 'Namespace';
            $className = 'get' . $name . 'Name';
            $useStr .= 'use ' . $this->$namespace() . '\\' .  $this->$className() . ";\n";
        }

        return $useStr;
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
