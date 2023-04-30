<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;
use Yjh94\StandardCodeGenerator\Utils\Indent;

class MigrationGeneratorController extends Controller
{
    use GenerateTrait;

    protected $generateType = 'migration';

    protected $stubFileName = 'migration.stub';

    protected $columnList = [];

    protected $variables = [
        'up', 'down',
    ];

    public function generate()
    {
        $columnList[] = Indent::make(8) . '$table->id();';

        foreach ($this->setting['fields'] as $column => $columnInfo) {
            $type = $columnInfo['type'] ?? 'string';
            $size = $columnInfo['size'] ?? 100;
            $default = $columnInfo['default'] ?? null;

            if ($type == 'string') {
                $code = "\$table->{$type}('{$column}', {$size})";
            } else {
                $code = "\$table->{$type}('{$column}')";
            }

            if ($this->isNullable($columnInfo)) $code .= '->nullable()';
            if ($default !== null) $code .= '->default(' . $default . ')';

            $columnList[] = Indent::make(12) . $code . ';';
        }

        $columnList[] = Indent::make(12) . '$table->timestamps();';
        $columnList[] = Indent::make(12) . '$table->softDeletes();';

        $this->columnList = $columnList;
        $this->create();
    }

    protected function isNullable($columnInfo)
    {
        $rules = $columnInfo['rules'] ?? '';
        $storeRules = $columnInfo['storeRules'] ?? '';
        $updateRules = $columnInfo['updateRules'] ?? '';
        $keyword = 'required';

        if (strpos($storeRules, $keyword) !== false) return false;
        if (strpos($updateRules, $keyword) !== false) return false;
        if (strpos($rules, $keyword) !== false) return false;

        return true;
    }

    protected function getUp()
    {
        $indent = Indent::make(8);
        $columnStr = implode("\n", $this->columnList);
        return <<< PHP
        Schema::create('{$this->tableName}', function (Blueprint \$table) {
            $columnStr
        {$indent}});
        PHP;
    }

    protected function getDown()
    {
        return "Schema::dropIfExists('{$this->tableName}');";
    }
}
