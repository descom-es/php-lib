<?php

namespace DescomLib\Services\Plesk;

use DescomLib\Exceptions\PermanentException;
use Exception;

class Subscription extends Plesk
{
    /**
     * Obtiene el dominio principal de la subscripción.
     *
     * @param string $domain
     * @return array|null
     */
    public function getPrimaryDomain(string $domain): ?string
    {
        try {
            $hosting = $this->getHosting('name', $domain);

            if ($hosting) {
                return explode('/', $hosting['www_root'])[4] ?? null;
            }
        } catch (Exception $e) {}

        return null;
    }

    /**
     * Obtiene el hosting de un dominio
     *
     * @param string $domain
     * @throws \DescomLib\Exceptions\PermanentException
     * @throws \PleskX\Api\Client\Exception
     * @return array|null
     */
    protected function getHosting(string $domain): ?array
    {
        if (!$this->client) {
            throw new PermanentException('plesk client not configured', 1);
        }

        return $this->client->site()->getHosting('name', $domain)->properties ?? null;
    }
}
