<?php

namespace NotificationChannels\Intercom\Tests;

use NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;
use NotificationChannels\Intercom\IntercomMessage;
use PHPUnit\Framework\TestCase;

class MessageIsNotCompleteExceptionTest extends TestCase
{
    public function testItReturnsMessageProvidedToConstruct(): void
    {
        $message = IntercomMessage::create('TEST');
        $exception = new MessageIsNotCompleteException($message);
        self::assertEquals($message, $exception->getIntercomMessage());
    }
}
