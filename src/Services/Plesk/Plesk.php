<?php

namespace DescomLib\Services\Plesk;

use PleskX\Api\Client as ClientPlesk;

abstract class Plesk
{
    protected $client;

    protected $host;

    protected $token;

    public function __construct($client = null)
    {
        $this->client = $client;
    }

    public function setClient(ClientPlesk $client)
    {
        $this->client = $client;

        return $this;
    }

    public function setHost(string $host)
    {
        $this->setClient(new ClientPlesk($host));

        return $this;
    }

    public function setToken(string $token)
    {
        if ($this->client) {
            $this->client->setSecretKey($token);
        }

        return $this;
    }


}
