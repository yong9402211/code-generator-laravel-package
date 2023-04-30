<?php

namespace Yjh94\StandardCodeGenerator\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateTrait
{
    use NameTrait;

    protected $className;

    protected $tableName;

    protected $outputPath = 'package/standard-code-generator/generates/outputs/';

    protected $stubDir = __DIR__ . '/../../stubs/';

    protected $packagePath = 'package/standard-code-generator/src/';

    protected $singularStudyName;

    protected $pluralStudyName;

    public function __construct(protected $setting = [], protected $name)
    {
        $this->setClassName($name, $this->generateType);
        $this->setTableName($this->name);
        $this->setSingularStudyName($this->name);
        $this->setPluralStudyName($this->name);
        $this->setFolderName($this->setting['folder'] ?? '');
    }

    /**
     * ************************************************************************
     * Getter & Setter
     * ************************************************************************
     */

    protected function setSingularStudyName($name)
    {
        $this->singularStudyName = Str::studly(Str::singular($name));
    }

    protected function getSingularStudyName()
    {
        return $this->singularStudyName;
    }

    protected function setPluralStudyName($name)
    {
        $this->pluralStudyName = Str::pluralStudly($name);
    }

    protected function getPluralStudyName()
    {
        return $this->pluralStudyName;
    }

    protected function setClassName($name, $generateType)
    {
        $this->className = $name . Str::studly(Str::singular($generateType));
    }

    protected function getClassName()
    {
        return $this->className;
    }

    protected function setTableName($name)
    {
        $this->tableName = Str::snake(Str::pluralStudly($name));
    }

    protected function getTableName()
    {
        return $this->tableName;
    }

    /**
     * ************************************************************************
     * Create File
     * ************************************************************************
     */

    protected function create()
    {
        $filePath = $this->getFilePath();
        $content = $this->generateFileContent();
        File::put($filePath, $content);
        $this->beautifyCode($filePath);
    }

    public function getFilePath()
    {
        $directory = $this->createDirectory();
        $fileName = $this->getFileName();

        $filePath = $directory . '/' . $fileName . '.php';
        // dd($filePath);
        return $filePath;
    }

    protected function getFileName()
    {
        return $this->getClassName();
    }

    protected function createDirectory()
    {
        $folderName = Str::pluralStudly($this->generateType);
        $directory = base_path($this->outputPath . '//' . $folderName . '/');
        $directory .= $this->getFolderName();
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        return $directory;
    }

    protected function generateFileContent()
    {
        $stubFile = $this->stubDir . $this->stubFileName;

        $stub = File::get($stubFile);

        $content = str_replace(
            $this->getVariables(),
            $this->getVariableValue(),
            $stub
        );

        return $content;
    }

    protected function beautifyCode($filePath)
    {
        $command = "vendor/bin/php-cs-fixer fix $filePath --rules=@PhpCsFixer";
        $output = [];
        $status = 0;

        exec($command, $output, $status);
    }

    protected function getVariables()
    {
        $values = [];
        foreach ($this->variables as $variable) {
            $values[] = '{{ ' . $variable . ' }}';
        }

        return $values;
    }

    protected function getVariableValue()
    {
        $values = [];
        foreach ($this->variables as $variable) {
            $method = 'get' . Str::ucfirst($variable);
            $values[] = $this->$method();
        }

        return $values;
    }

    protected function convertArray($array)
    {
        // Split the array into chunks of 3 elements
        $chunks = array_chunk($array, 3);

        // Concatenate the chunks into multiple lines
        $lines = [];
        foreach ($chunks as $chunk) {
            $line = implode(', ', array_map(function ($item) {
                return '"' . $item . '"';
            }, $chunk));
            $lines[] = $line;
        }

        // Concatenate the lines into a single string
        $result = '[' . "\n" . '    ' . implode(',' . "\n" . '    ', $lines) . "\n" . '];';

        return $result;
    }
}
