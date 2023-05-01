<?php

namespace Yjh94\StandardCodeGenerator\Console\Commands;

use Illuminate\Console\Command;
use Yjh94\StandardCodeGenerator\Http\Controllers\ControllerGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\MigrationGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\ModelGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\RequestGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\RouteGeneratorController;
use Yjh94\StandardCodeGenerator\Http\Controllers\ServiceGeneratorController;
use Illuminate\Support\Str;

class GeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yjh:code {name} {--M|model} {--C|controller} {--R|route} {--S|service} {--MI|migration} {--RS|store-request} {--RU|update-request} {--no-model} {--no-controller} {--no-service} {--no-route} {--no-migration} {--no-store-request} {--no-update-request}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate standard code';

    protected $optionList = [
        'model', 'controller', 'service',
        'route', 'migration',
        'store-request', 'update-request',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->info('Creating standard code for ' . $name);
        $setting = $this->readSetting($name);

        $total = $this->autoGenerate($setting, $name, true);
        if ($total == 0) {
            $this->autoGenerate($setting, $name, false);
        }

        // TODO: model generate foreign class
        // TODO: default setting
        // TODO: validation number
        // TODO: decimal type, more type
    }

    protected function autoGenerate($setting, $name, $checkOption)
    {
        $created = 0;
        foreach ($this->optionList as $option) {
            if ($checkOption) {
                if (!$this->option($option))
                    continue;
            } else {
                if ($this->option('no-' . $option))
                    continue;
            }

            $optionName = $option;
            if ($option == 'store-request' || $option == 'update-request') {
                $optionName = 'request';
            }

            $method = 'Yjh94\StandardCodeGenerator\Http\Controllers\\' . Str::studly($optionName) . 'GeneratorController';
            $g = new $method($setting, $name);

            if ($option == 'store-request') {
                $g->generate('store');
            } else if ($option == 'update-request') {
                $g->generate('update');
            } else {
                $g->generate();
            }

            $created++;
        }

        return $created;
    }

    protected function readSetting($name)
    {
        $inputDir = config('yjh_generate.input_dir');
        $json = file_get_contents($inputDir . $name . '.json');
        return json_decode($json, true);
    }
}
