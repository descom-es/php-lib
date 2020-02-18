<?php

namespace Tests;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
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

        $response = $notificationManager->send($data);

        $this->assertEquals($responseExpected, (array)$response);
    }

    public function testLoggedEmailPermanentException()
    {
        $this->expectException(PermanentException::class);

        $data = [];

        $mock = new MockHandler([new Response(404, [], null)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManager;
        $notificationManager->setClient($client);

        $response = $notificationManager->send($data);
    }

    public function testLoggedEmailTemporaryException()
    {
        $this->expectException(TemporaryException::class);

        $data = [];

        $mock = new MockHandler([new Response(503, [], null)]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $notificationManager = new NotificationManager;
        $notificationManager->setClient($client);

        $response = $notificationManager->send($data);
    }
}
