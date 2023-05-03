<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
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

    protected $columnString = [
        'char', 'string',
    ];

    protected $columnInteger = [
        'bigInteger', 'integer', 'mediumInteger',
        'smallInteger', 'tinyInteger', 'unsignedInteger',
        'unsignedBigInteger', 'unsignedMediumInteger', 'unsignedSmallInteger',
        'unsignedTinyInteger',
    ];

    protected $columnFloat = [
        'decimal', 'double', 'float',
        'unsignedDecimal',
    ];

    protected $ruleSeq = [
        'required', 'string', 'numeric',
        'integer',
        'min', 'max', 'between',
        'unique', 'nullable'
    ];

    public function generate($requestType)
    {
        $this->requestType = $requestType;
        $typeRule = $requestType . 'Rules';

        foreach ($this->setting['fields'] as $column => $columnInfo) {
            $rules = $columnInfo['rules'] ?? '';
            $typeRules = $columnInfo[$typeRule] ?? '';

            $this->rules[$column] = $this->autoRules($column, $columnInfo);

            $separator = '';
            if (isset($this->rules[$column]) && !empty($this->rules[$column])) {
                $separator = '|';
            }

            if ($typeRules != '') {
                $this->rules[$column] .= $separator . $typeRules;
            } else if ($rules != '') {
                $this->rules[$column] .= $separator . $rules;
            }

            $this->rules[$column] = $this->reOrderRules($this->rules[$column]);
        }



        $this->create();
    }

    protected function autoRules($column, $columnInfo)
    {
        $columnType = $columnInfo['type'] ?? '';
        $columnSize = $columnInfo['size'] ?? 0;

        return $this->autoSizeRule($column, $columnType, $columnSize);
    }

    protected function autoSizeRule($column, $columnType, $columnSize)
    {
        if (in_array($columnType, $this->columnString)) return 'max:' . $columnSize;
        if (in_array($columnType, $this->columnInteger)) return 'numeric|max_digits:' . $columnSize;

        if (in_array($columnType, $this->columnFloat)) {
            $floatSizeStr = '';
            $floatSizes = explode(',', $columnSize);
            $floatSize = (int) $floatSizes[0];
            $decimalPoint = (int) $floatSizes[1] ?? 0;
            $floatSize -= $decimalPoint;
            for ($i = 0; $i < $floatSize; $i++)
                $floatSizeStr .= '9';
            $floatSizeStr .= '.';
            for ($i = 0; $i < $decimalPoint; $i++)
                $floatSizeStr .= '9';
            return 'numeric|max:' . $floatSizeStr;
        };
    }

    protected function reOrderRules($rulesStr)
    {
        $newRules = [];
        $currRules = explode('|', $rulesStr);
        foreach ($this->ruleSeq as $rule) {
            $length = Str::length($rule);
            foreach ($currRules as $currKey => $currRule) {
                if (Str::substr($currRule, 0, $length) == $rule) {
                    $newRules[] = $currRule;
                    unset($currRules[$currKey]);
                }
            }
        }

        foreach ($currRules as $currKey => $currRule) {
            $newRules[] = $currRule;
        }

        return implode('|', $newRules);
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
