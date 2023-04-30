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

    protected $skips = ['id', 'uuid'];

    public function generate()
    {
        $indent = Indent::make(12);
        $columnList[] = Indent::make(8) . '$table->id();';
        $columnList[] = "{$indent}\$table->uuid('uuid')->default(DB::raw('(UUID())'));";

        foreach ($this->setting['fields'] as $column => $columnInfo) {
            if (in_array($column, $this->skips))
                continue;

            $type = $columnInfo['type'] ?? 'string';
            $size = $columnInfo['size'] ?? 100;
            $default = $columnInfo['default'] ?? null;

            if ($type == 'string') {
                $code = "\$table->{$type}('{$column}', {$size})";
            } else if ($type == 'foreign') {
                $foreignKey = $columnInfo['foreignKey'] ?? [];
                $referenceTable = $foreignKey[0] ?? '';
                $referenceKey = $foreignKey[1] ?? 'uuid';

                if ($referenceTable == '')
                    $referenceTable = Str::plural(Str::replace('_id', '', $column));

                $code = "\$table->{$type}('{$column}')->references('{$referenceKey}')->on('{$referenceTable}')";
            } else {
                $code = "\$table->{$type}('{$column}')";
            }

            if ($this->isNullable($columnInfo)) $code .= '->nullable()';
            if ($default !== null) $code .= '->default(' . $default . ')';

            $columnList[] = $indent . $code . ';';
        }

        $columnList[] = $indent . '$table->timestamps();';
        $columnList[] = $indent . '$table->softDeletes();';

        $this->columnList = $columnList;
        $this->create();
    }

    protected function isNullable($columnInfo)
    {
        $rules = $columnInfo['rules'] ?? '';
        $storeRules = $columnInfo['storeRules'] ?? '';
        $updateRules = $columnInfo['updateRules'] ?? '';
        $nullable = $columnInfo['nullable'] ?? true;
        $keyword = 'required';

        if (strpos($storeRules, $keyword) !== false) return false;
        if (strpos($updateRules, $keyword) !== false) return false;
        if (strpos($rules, $keyword) !== false) return false;
        if (!$nullable) return false;

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
