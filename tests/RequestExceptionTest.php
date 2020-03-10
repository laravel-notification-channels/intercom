<?php

namespace NotificationChannels\Intercom\Tests;

use GuzzleHttp\Exception\RequestException as BaseRequestException;
use GuzzleHttp\Psr7\Request;
use NotificationChannels\Intercom\Exceptions\RequestException;
use PHPUnit\Framework\TestCase;

class RequestExceptionTest extends TestCase
{
    public function testItReturnsBaseExceptionProvidedToConstruct(): void
    {
        $baseException = new BaseRequestException(
            'It is an exception',
            new Request('get', 'http://foo.bar')
        );
        $exception = new RequestException($baseException);
        self::assertEquals($exception->getBaseException(), $baseException);
    }
}
