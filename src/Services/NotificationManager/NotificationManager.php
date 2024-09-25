<?php

namespace DescomLib\Services\NotificationManager;

use DescomLib\Exceptions\PermanentException;
use DescomLib\Exceptions\TemporaryException;
use DescomLib\Services\NotificationManager\Events\NotificationFailed;
use Exception;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18Client;
use Illuminate\Support\Facades\Event;
use Psr\Http\Client\ClientInterface;

class NotificationManager
{
    protected $client;
    protected $url;
    protected $token;
    protected $requestFactory;
    protected $streamFactory;

    /**
     * Create a new NotificationManager Instance.
     */
    public function __construct()
    {
        $this->client = new Psr18Client();

        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $this->url = config('descom_lib.notification_manager.url');
        $this->token = config('descom_lib.notification_manager.token');
    }

    /**
     * To mock
     *
     * @param ClientInterface $client
     * @return self
     */
    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;
        return $this;
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
            $request = $this->requestFactory->createRequest('POST', $this->url)
                ->withHeader('Accept', 'application/json')
                ->withHeader('Authorization', $this->token)
                ->withBody($this->streamFactory->createStream(json_encode($data)));

            $response = $this->client->sendRequest($request);

            if ($response->getStatusCode() < 300) {
                return json_decode($response->getBody()->getContents());
            }

            if ($response->getStatusCode() == 503) {
                Event::dispatch(new NotificationFailed(
                    $data,
                    new TemporaryException("Temporal error", $response->getStatusCode())
                ));
            } else {
                Event::dispatch(new NotificationFailed(
                    $data,
                    new PermanentException("Permanent error", $response->getStatusCode())
                ));
            }
        } catch (Exception $e) {
            Event::dispatch(new NotificationFailed(
                $data,
                new TemporaryException($e->getMessage(), $e->getCode())
            ));
        }

        return null;
    }
}
