<?php

namespace Tests;

use Tests\TestCase;
use DescomLib\Exceptions\PermanentException;
use DescomLib\Exceptions\TemporaryException;
use DescomLib\Services\NotificationManager\Events\NotificationFailed;
use DescomLib\Services\NotificationManager\NotificationManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

class NotificationManagerTest extends TestCase
{
    public function testLoggedEmail()
    {
        $data = [];
        $responseExpected = [
            'message' => 'Notificaciones enviadas con éxito'
        ];

        // Simula la respuesta HTTP
        Http::fake([
            config('descom_lib.notification_manager.url') => Http::sequence()->push($responseExpected)
        ]);

        $notificationManager = new NotificationManager;

        $response = $notificationManager->send($data);

        $this->assertEquals($responseExpected, (array)$response);
    }

    public function testLoggedEmailPermanentException()
    {
        Event::fake();

        $data = [];

        // Simula la respuesta HTTP con un código 404
        Http::fake([
            config('descom_lib.notification_manager.url') => Http::response(null, 404)
        ]);

        $notificationManager = new NotificationManager;

        $notificationManager->send($data);

        Event::assertDispatched(NotificationFailed::class, function (NotificationFailed $event) {
            return $event->exception instanceof PermanentException;
        });
    }

    public function testLoggedEmailTemporaryException()
    {
        Event::fake();

        $data = [];

        // Simula la respuesta HTTP con un código 503
        Http::fake([
            config('descom_lib.notification_manager.url') => Http::response(null, 503)
        ]);

        $notificationManager = new NotificationManager;

        $notificationManager->send($data);

        Event::assertDispatched(NotificationFailed::class, function (NotificationFailed $event) {
            return $event->exception instanceof TemporaryException;
        });
    }
}
