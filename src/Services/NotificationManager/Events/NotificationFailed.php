<?php

namespace DescomLib\Services\NotificationManager\Events;

use Exception;

class NotificationFailed
{
    public $exception;

    public $data;


    public function __construct(array $data, Exception $exception)
    {
        $this->data = $data;

        $this->exception = $exception;
    }
}
