<?php

namespace DescomLib\Services\NotificationManager;

use Illuminate\Support\Facades\Http;
use DescomLib\Exceptions\PermanentException;
use DescomLib\Exceptions\TemporaryException;
use DescomLib\Services\NotificationManager\Events\NotificationFailed;
use Illuminate\Support\Facades\Event;

class NotificationManager
{
    protected $url = null;
    protected $token = null;

    /**
     * Create a new NotificationManager Instance.
     */
    public function __construct()
    {
        $this->url = config('descom_lib.notification_manager.url');
        $this->token = config('descom_lib.notification_manager.token');
    }

    /**
     * Send request to Notification Manager service
     *
     * @param array $data
     * @return object|null
     */
    public function send(array $data): ?object
    {
        try {
            $response = Http::withHeaders([
                'Accept'        => 'application/json',
                'Authorization' => $this->token,
            ])->timeout(30)->post($this->url, $data);

            if ($response->successful()) {
                return $response->object(); // Retorna como un objeto
            }

            if ($response->status() === 503) {
                Event::dispatch(new NotificationFailed(
                    $data,
                    new TemporaryException("Temporary error", $response->status())
                ));
            } else {
                Event::dispatch(new NotificationFailed(
                    $data,
                    new PermanentException("Permanent error", $response->status())
                ));
            }
        } catch (\Exception $e) {
            Event::dispatch(new NotificationFailed(
                $data,
                new TemporaryException($e->getMessage(), $e->getCode())
            ));
        }

        return null;
    }
}
