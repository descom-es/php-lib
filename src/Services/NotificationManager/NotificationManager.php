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
        $this->url = config('descom_lib.notification_manager.url');
        $this->token = config('descom_lib.notification_manager.token');
    }

    /**
     * To mock
     *
     * @param GuzzleHttp\Client $client
     * @return self
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Send request
     *
     * @param array $data
     * @throws DescomLib\Exceptions\TemporaryException
     * @throws DescomLib\Exceptions\PermanentException
     * @return object
     */
    public function send($data)
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->url,
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

            throw new PermanentException("Permanent error", $response->getStatusCode());
        } catch (RequestException $e) {
            throw new TemporaryException($e->getMessage(), $e->getCode());
        }
    }
}
