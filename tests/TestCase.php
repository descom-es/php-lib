<?php

namespace DescomLib\Tests;

use DescomLib\DescomLibServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

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
            DescomLibServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('descom_lib.notification_manager.url', "http://notification-manager.descom.es/api");
        $app['config']->set('descom_lib.notification_manager.token', "");
    }
}
