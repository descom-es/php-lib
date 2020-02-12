<?php

namespace Descom\NotificationManager\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Descom\NotificationManager\NotificationManagerClass;

class NotificationManagerTest extends TestCase
{
    public function testLoggedEmail()
    {
        $data = [];

        $mock = new MockHandler([new Response(200, [], null)]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManagerClass();
        $notificationManager->client = $client;

        $response = $notificationManager->send("logged_email", $data);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLoggedEmailClientNotFound()
    {
        $data = [];

        $mock = new MockHandler([new Response(404, [], null)]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManagerClass();
        $notificationManager->client = $client;

        $response = $notificationManager->send("logged_email", $data);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testLoggedEmailValidationErrors()
    {
        $data = [];

        $mock = new MockHandler([new Response(422, [], null)]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManagerClass();
        $notificationManager->client = $client;

        $response = $notificationManager->send("logged_email", $data);

        $this->assertEquals(422, $response->getStatusCode());
    }
}
