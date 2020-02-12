<?php

namespace Descom\NotificationManager\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Descom\NotificationManager\NotificationManagerServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            NotificationManagerServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('notification_manager.url', "http://notification-manager.descom.es/api");
        $app['config']->set('notification_manager.token', "");
    }
}
