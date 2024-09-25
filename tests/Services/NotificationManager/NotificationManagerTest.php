<?php

namespace Tests;

use DescomLib\Exceptions\PermanentException;
use DescomLib\Exceptions\TemporaryException;
use DescomLib\Services\NotificationManager\Events\NotificationFailed;
use DescomLib\Services\NotificationManager\NotificationManager;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client as MockClient;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NotificationManagerTest extends TestCase
{
    public function testLoggedEmail()
    {
        $data = [];
        $responseExpected = [
            'message' => 'Notificaciones enviadas con éxito'
        ];

        $mockClient = new MockClient();
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        $response = $responseFactory->createResponse(200)
            ->withBody($streamFactory->createStream(json_encode($responseExpected)));

        $mockClient->addResponse($response);

        $notificationManager = new NotificationManager;
        $notificationManager->setClient($mockClient);

        $response = $notificationManager->send($data);

        $this->assertEquals($responseExpected, (array)$response);
    }

    public function testLoggedEmailPermanentException()
    {
        Event::fake();

        $data = [];

        $mockClient = new MockClient();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $response = $responseFactory->createResponse(404);
        $mockClient->addResponse($response);

        $notificationManager = new NotificationManager;
        $notificationManager->setClient($mockClient);

        $notificationManager->send($data);

        Event::assertDispatched(NotificationFailed::class, function (NotificationFailed $event) {
            return $event->exception instanceof PermanentException;
        });
    }

    public function testLoggedEmailTemporaryException()
    {
        Event::fake();

        $data = [];

        $mockClient = new MockClient();

        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $response = $responseFactory->createResponse(503);
        $mockClient->addResponse($response);


        $notificationManager = new NotificationManager;
        $notificationManager->setClient($mockClient);

        $notificationManager->send($data);

        Event::assertDispatched(NotificationFailed::class, function (NotificationFailed $event) {
            return $event->exception instanceof TemporaryException;
        });
    }
}
