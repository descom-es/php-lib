<?php

namespace Tests;

use DescomLib\Services\Plesk\Subscription;
use PleskX\Api\Client;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    public function test_get_primary_domain()
    {
        $subscription = new Subscription(
            $this->mock(Client::class, function ($mock) {
                $mock->shouldReceive('site')->andReturn(new class {
                    public function getHosting() {
                        return new class {
                            public $properties = [
                                'www_root' => '/var/www/vhosts/pepe.com/httpdocs',
                            ];
                        };
                    }
                });
            })
        );

        $domainPrincipal = $subscription->getPrimaryDomain('example.com');

        $this->assertEquals('pepe.com', $domainPrincipal);
    }

    public function test_get_primary_domain_exception()
    {
        $subscription = new Subscription(
            $this->mock(Client::class, function ($mock) {
                $mock->shouldReceive('site')->andReturn(new class {
                    public function getHosting() {
                        return null;
                    }
                });
            })
        );

        $domainPrincipal = $subscription->getPrimaryDomain('example.com');

        $this->assertNull($domainPrincipal);
    }
}
