<?php

namespace Descom\NotificationManager;

use Illuminate\Support\ServiceProvider;

class NotificationManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/notification_manager.php' => config_path('notification_manager.php'),
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
