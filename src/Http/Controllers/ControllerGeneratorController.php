<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;

class ControllerGeneratorController extends Controller
{
    use GenerateTrait;

    protected $generateType = 'controller';

    protected $stubFileName = 'controller.stub';

    protected $routeList = [];

    protected $routePrefix = '';

    protected $variables = [
        'namespace', 'use', 'controllerName', 'modelFullName',
        'serviceName', 'storeRequestName', 'updateRequestName',
    ];

    public function generate()
    {
        $this->create();
    }

    /**
     * ************************************
     * Start for variables
     * ************************************
     */

    public function getNamespace()
    {
        return $this->getControllerNamespace();
    }

    public function getUse()
    {
        $useStr = '';
        $useList = ['Service', 'StoreRequest', 'UpdateRequest'];
        foreach ($useList as $name) {
            $namespace = 'get' . $name . 'Namespace';
            $className = 'get' . $name . 'Name';
            $useStr .= 'use ' . $this->$namespace() . '\\' .  $this->$className() . ";\n";
        }

        return $useStr;
    }

    /**
     * ************************************
     * End for variables
     * ************************************
     */
}
