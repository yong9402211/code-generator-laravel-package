<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;

class ServiceGeneratorController extends Controller
{
    use GenerateTrait;

    protected $generateType = 'service';

    protected $stubFileName = 'service.stub';

    protected $routeList = [];

    protected $routePrefix = '';

    protected $variables = [
        'namespace', 'use', 'serviceName',
        'modelName',
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
        return $this->getServiceNamespace();
    }

    public function getUse()
    {
        $useStr = '';
        $useList = ['Model'];
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
