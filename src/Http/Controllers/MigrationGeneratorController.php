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

    protected $withSizes = [
        'char', 'string'
    ];

    protected $withValues = [
        'enum', 'set'
    ];

    protected $withPrecisions = [
        'dateTimeTz', 'dateTime', 'softDeletesTz',
        'softDeletes', 'timeTz', 'time',
        'timestampTz', 'timestamp',
    ];

    protected $withDecimals = [
        'decimal', 'double', 'float',
        'unsignedDecimal', ''
    ];

    public function generate()
    {
        $indent = Indent::make(12);
        // $columnList[] = Indent::make(8) . '$table->id();';
        $columnList[] = Indent::make(8) . "\$table->uuid('uuid')->default(DB::raw('(UUID())'))->primary();";

        foreach ($this->setting['fields'] as $column => $columnInfo) {
            if (in_array($column, $this->skips))
                continue;

            $type = $columnInfo['type'] ?? 'string';
            $size = $columnInfo['size'] ?? 100;
            $default = $columnInfo['default'] ?? null;

            $code = '';
            $subCode = '';

            if (in_array($type, $this->withSizes) || in_array($type, $this->withDecimals) || in_array($type, $this->withPrecisions)) {
                $code = "\$table->{$type}('{$column}', {$size})";
            } else if (in_array($type, $this->withValues)) {
                $options = $columnInfo['options'] ?? [];
                $options = $this->convertArray($options, 16);
                $options = str_replace(';', '', $options);
                $code = "\$table->{$type}('{$column}', {$options})";
            } else if ($type == 'foreign') {
                $foreignKey = $columnInfo['foreignKey'] ?? [];
                $referenceTable = $foreignKey[0] ?? '';
                $referenceKey = $foreignKey[1] ?? 'uuid';

                if ($referenceTable == '')
                    $referenceTable = Str::plural(Str::replace('_id', '', $column));

                $code = "\$table->char('{$column}', 36)";
                $subCode = "\$table->{$type}('{$column}')->references('{$referenceKey}')->on('{$referenceTable}');";
            } else {
                $code = "\$table->{$type}('{$column}')";
            }

            if ($this->isNullable($columnInfo)) $code .= '->nullable()';
            if ($default !== null) $code .= '->default(' . $default . ')';

            $columnList[] = $indent . $code . ';';

            if ($subCode != '')
                $columnList[] = $indent . $subCode;
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
