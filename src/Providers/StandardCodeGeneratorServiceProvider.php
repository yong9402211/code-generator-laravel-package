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
    }

    public function register()
    {
    }
}
