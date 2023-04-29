<?php

namespace Yjh94\StandardCodeGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yjh94\StandardCodeGenerator\Http\Controllers\MigrationGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\ModelGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\RouteGeneratorController;

class GeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yjh:code {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate standard code';

    protected $className;

    protected $variables = [
        'namespace', 'class', 'methods',
        'fillable', 'hidden', 'storable', 'updatable'
    ];

    protected $stubFile = __DIR__ . '/../../../stubs/model.stub';

    protected $inputDir = __DIR__ . '/../../../generates/inputs/';

    protected $settings = [];
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->info('Creating standard code for ' . $name);
        $setting = $this->readSetting($name);

        $g = new ModelGeneratorController($setting, $name);
        $g->generate();

        $g = new MigrationGeneratorController($setting, $name);
        $g->generate();

        $g = new RouteGeneratorController($setting, $name);
        $g->generate();

        // TODO: controller
        // TODO: service
        // TODO: request
        // TODO: with folder name
        // TODO: default setting
    }

    protected function readSetting($name)
    {
        $json = file_get_contents($this->inputDir . $name . '.json');
        return json_decode($json, true);
    }
}
