<?php

namespace Yjh94\StandardCodeGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yjh94\StandardCodeGenerator\Http\Controllers\ControllerGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\MigrationGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\ModelGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\RequestGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\RouteGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\ServiceGeneratorController;

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

        $g = new ControllerGeneratorController($setting, $name);
        $g->generate();

        $g = new ServiceGeneratorController($setting, $name);
        $g->generate();

        $g = new RequestGeneratorController($setting, $name);
        $g->generate('store');

        $g = new RequestGeneratorController($setting, $name);
        $g->generate('update');

        // TODO: default setting
    }

    protected function readSetting($name)
    {
        $inputDir = config('yjh_generate.input_dir');
        $json = file_get_contents($inputDir . $name . '.json');
        return json_decode($json, true);
    }
}
