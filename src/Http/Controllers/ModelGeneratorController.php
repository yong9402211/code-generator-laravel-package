<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;

class ModelGeneratorController extends Controller
{
    use GenerateTrait;

    protected $generateType = 'model';

    protected $stubFileName = 'model.stub';

    protected $hiddenList = [];
    protected $fillableList = [];
    protected $storableList = [];
    protected $updatableList = [];

    protected $variables = [
        'namespace', 'class', 'methods',
        'fillable', 'hidden', 'storable', 'updatable'
    ];

    public function generate()
    {
        foreach ($this->setting['fields'] as $column => $columnInfo) {
            $hidden = $columnInfo['hidden'] ?? false;
            $fillable = $columnInfo['fillable'] ?? true;
            $storable = $columnInfo['storable'] ?? true;
            $updatable = $columnInfo['updatable'] ?? true;

            if ($hidden) $this->hiddenList[] = $column;
            if ($fillable) $this->fillableList[] = $column;
            if ($storable) $this->storableList[] = $column;
            if ($updatable) $this->updatableList[] = $column;
        }

        $this->create();
    }

    protected function setClassName($name, $generateType)
    {
        $this->className = $name;
    }

    protected function getNamespace()
    {
        return 'App\Models';
    }

    protected function getClass()
    {
        return $this->className;
    }

    protected function getMethods()
    {
        return '';
    }

    protected function getHidden()
    {
        return 'protected $hidden = ' . $this->convertArray($this->hiddenList);
    }

    protected function getFillable()
    {
        return 'protected $fillable = ' . $this->convertArray($this->fillableList);
    }

    protected function getStorable()
    {
        return 'protected $storetable = ' . $this->convertArray($this->storableList);
    }

    protected function getUpdatable()
    {
        return 'protected $updateable = ' . $this->convertArray($this->updatableList);
    }
}
