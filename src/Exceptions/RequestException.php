<?php

namespace NotificationChannels\Intercom\Exceptions;

use GuzzleHttp\Exception\RequestException as BaseRequestException;
use Throwable;

class RequestException extends IntercomException
{
    /**
     * @var BaseRequestException
     */
    private $baseException;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        BaseRequestException $baseException,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->baseException = $baseException;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return BaseRequestException
     */
    public function getBaseException(): BaseRequestException
    {
        return $this->baseException;
    }
}
