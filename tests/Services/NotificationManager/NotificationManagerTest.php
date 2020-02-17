<?php

namespace DescomLib\Tests\Services\NotificationManager;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use DescomLib\Tests\TestCase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use DescomLib\Exceptions\PermanentException;
use DescomLib\Exceptions\TemporaryException;
use DescomLib\Services\NotificationManager\NotificationManager;

class NotificationManagerTest extends TestCase
{
    public function testLoggedEmail()
    {
        $data = [];
        $responseExpected = [
            'message' => 'Notificaciones enviadas con éxito'
        ];

        $mock = new MockHandler([new Response(200, [], json_encode($responseExpected))]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManager;
        $notificationManager->setClient($client);

        $response = $notificationManager->send("logged_email", $data);

        $this->assertEquals($responseExpected, $response);
    }

    public function testLoggedEmailPermanentException()
    {
        $data = [];

        $mock = new MockHandler([new Response(404, [], null)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManager;
        $notificationManager->setClient($client);

        $response = $notificationManager->send("logged_email", $data);

        $this->assertEquals(get_class(PermanentException), get_class($response));
    }

    public function testLoggedEmailTemporaryException()
    {
        $data = [];

        $mock = new MockHandler([new Response(503, [], null)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManager;
        $notificationManager->setClient($client);

        $response = $notificationManager->send("logged_email", $data);

        $this->assertEquals(get_class(TemporaryException), get_class($response));
    }
}
