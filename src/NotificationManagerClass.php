<?php

namespace Descom\NotificationManager;

use GuzzleHttp\Client;

class NotificationManagerClass
{
    public $client = null;
    protected $url = null;
    protected $token = null;

    /**
     * Create a new NotificationsPackage Instance.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->url = config('notification_manager.url');
        $this->token = config('notification_manager.token');
    }

    public function send($function, $data)
    {
        return $this->client->request(
            'POST',
            $this->url . '/' . $function,
            [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => $this->token
                ],
                'http_errors'     => false,
                'connect_timeout' => 30,
                'json'            => $data
            ]
        );
    }
}
