<?php

namespace Yjh94\StandardCodeGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Yjh94\StandardCodeGenerator\Console\Commands\GeneratorCommand;

class StandardCodeGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeneratorCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/yjh_generate.php' => config_path('yjh_generate.php'),
        ], 'yjh-generate-config');

        $this->publishes([
            __DIR__ . '/../../publish/Models/' => app_path('Models'),
            __DIR__ . '/../../publish/Traits/' => app_path('Traits'),
            __DIR__ . '/../../publish/Resources/' => app_path('Http/Resources'),
        ], 'yjh-generate-base');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/yjh_generate.php',
            'yjh_generate'
        );
    }
}
