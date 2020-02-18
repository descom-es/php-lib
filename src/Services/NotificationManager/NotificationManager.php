<?php

namespace DescomLib\Services\NotificationManager;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use DescomLib\Exceptions\PermanentException;
use DescomLib\Exceptions\TemporaryException;

class NotificationManager
{
    protected $client = null;
    protected $url = null;
    protected $token = null;

    /**
     * Create a new NotificationManager Instance.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->url = config('notification_manager.url');
        $this->token = config('notification_manager.token');
    }

    /**
     * Undocumented function
     *
     * @param Client $client
     * @return void
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Undocumented function
     *
     * @param [type] $function
     * @param [type] $data
     * @return void
     */
    public function send($function, $data)
    {
        try {
            $response = $this->client->request(
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

            if ($response->getStatusCode() < 300) {
                return json_decode($response->getBody()->getContents());
            }
            if ($response->getStatusCode() == 503) {
                throw new TemporaryException("Temporal error", 503);
            }
            throw new PermanentException("Permanent error", 503);
        } catch (RequestException $e) {
            throw new TemporaryException($e->getMessage(), 503);
        }
    }
}
