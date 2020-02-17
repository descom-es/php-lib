<?php
namespace DescomLib\Exceptions;

use Exception;

/**
 * Exception temporal al aprovisionar en nutrium
 */
class TemporaryException extends Exception
{
    public function __contruct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. self::class);
        }

        parent::__construct($message, $code);
    }
}
