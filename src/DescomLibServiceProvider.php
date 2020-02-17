<?php

namespace DescomLib;

use Illuminate\Support\ServiceProvider;

class DescomLibServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/descom_lib.php' => config_path('descom_lib.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'skeleton');
    }
}
