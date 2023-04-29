<?php

namespace Yjh94\StandardCodeGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Yjh94\StandardCodeGenerator\Traits\GenerateTrait;

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
        $columnList[] = '$table->id();';
        foreach ($this->setting['fields'] as $column => $columnInfo) {
            $type = $columnInfo['type'] ?? 'string';
            $size = $columnInfo['size'] ?? 100;
            $rules = $columnInfo['rules'] ?? '';
            $default = $columnInfo['default'] ?? null;


            if ($type == 'string') {
                $code = "\$table->{$type}('{$column}', {$size})";
            } else {
                $code = "\$table->{$type}('{$column}')";
            }

            if (strpos($rules, 'required') === false) $code .= '->nullable()';
            if ($default !== null) $code .= '->default(' . $default . ')';

            $columnList[] = $code . ';';
        }

        $columnList[] = '$table->timestamps();';
        $columnList[] = '$table->softDeletes();';

        $this->columnList = $columnList;
        $this->create();
    }

    protected function getUp()
    {
        $columnStr = implode("\n", $this->columnList);
        return <<< PHP
        Schema::create('{$this->tableName}', function (Blueprint \$table) {
            $columnStr
        });
        PHP;
    }

    protected function getDown()
    {
        return "Schema::dropIfExists('{$this->tableName}');";
    }
}
