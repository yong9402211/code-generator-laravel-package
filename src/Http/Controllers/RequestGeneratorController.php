<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;
use Yjh94\StandardCodeGenerator\Utils\Indent;

class RequestGeneratorController extends Controller
{
    use GenerateTrait;

    protected $generateType = 'request';

    protected $stubFileName = 'request.stub';

    protected $routeList = [];

    protected $routePrefix = '';

    protected $requestType = '';

    protected $rules = [];

    protected $variables = [
        'namespace', 'requestName', 'rules',
    ];

    public function generate($requestType)
    {
        $this->requestType = $requestType;
        $typeRule = $requestType . 'Rules';

        foreach ($this->setting['fields'] as $column => $columnInfo) {
            $rules = $columnInfo['rules'] ?? '';
            $typeRules = $columnInfo[$typeRule] ?? '';
            if ($typeRules != '') {
                $this->rules[$column] = $typeRules;
            } else if ($rules != '') {
                $this->rules[$column] = $rules;
            }
        }

        $this->create();
    }

    /**
     * ************************************
     * Start for variables
     * ************************************
     */

    public function getNamespace()
    {
        if ($this->requestType == 'store')
            return $this->getStoreRequestNamespace();
        else if ($this->requestType == 'update')
            return $this->getUpdateRequestNamespace();

        return '';
    }

    public function getRequestName()
    {
        if ($this->requestType == 'store')
            return $this->getStoreRequestName();
        else if ($this->requestType == 'update')
            return $this->getUpdateRequestName();

        return '';
    }

    public function getRules()
    {
        $string = str_replace("array (", "[", var_export($this->rules, true)  . ';');
        $string = Indent::whitespaceToIndent($string, 12);
        $string = str_replace(");", Indent::make(8) . "]", $string);
        return $string;
    }

    /**
     * ************************************
     * End for variables
     * ************************************
     */
}
