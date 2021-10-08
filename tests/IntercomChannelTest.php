<?php

namespace NotificationChannels\Intercom\Tests;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use Intercom\IntercomClient;
use Intercom\IntercomMessages;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;
use NotificationChannels\Intercom\Exceptions\RequestException;
use NotificationChannels\Intercom\IntercomChannel;
use NotificationChannels\Intercom\IntercomMessage;
use NotificationChannels\Intercom\Tests\Mocks\TestNotifiable;
use NotificationChannels\Intercom\Tests\Mocks\TestNotification;

class IntercomChannelTest extends MockeryTestCase
{
    /**
     * @var IntercomMessages|Mock
     */
    private $intercomMessages;

    /**
     * @var IntercomClient
     */
    private $intercom;

    /**
     * @var IntercomChannel
     */
    private $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->intercom = new IntercomClient('', null);
        $this->intercomMessages = Mockery::mock(IntercomMessages::class, $this->intercom);
        $this->intercom->messages = $this->intercomMessages;
        $this->channel = new IntercomChannel($this->intercom);
    }

    public function testItCanSendMessage(): void
    {
        $notification = new TestNotification(
            IntercomMessage::create('Hello World!')
                ->fromAdminId(123)
                ->toUserId(321)
        );

        $this->intercomMessages->shouldReceive('create')
            ->once()
            ->with([
                'body'         => 'Hello World!',
                'message_type' => 'inapp',
                'from'         => [
                    'type' => 'admin',
                    'id'   => '123',
                ],
                'to'           => [
                    'type' => 'user',
                    'id'   => '321',
                ],
            ]);

        $this->channel->send(new TestNotifiable(), $notification);
        $this->assertPostConditions();
    }

    public function testItThrowsAnExceptionWhenRecipientIsNotProvided(): void
    {
        $notification = new TestNotification(
            IntercomMessage::create('Hello World!')
                ->fromAdminId(123)
        );

        $this->expectException(MessageIsNotCompleteException::class);
        $this->channel->send(new TestNotifiable(), $notification);
    }

    public function testItThrowsAnExceptionSomeOfRequiredParamsAreNotDefined(): void
    {
        $notification = new TestNotification(
            IntercomMessage::create()
                ->fromAdminId(123)
                ->toUserId(321)
        );

        $this->expectException(MessageIsNotCompleteException::class);
        $this->channel->send(new TestNotifiable(), $notification);
    }

    public function testItThrowsRequestExceptionOnGuzzleBadResponseException(): void
    {
        $this->intercomMessages->shouldReceive('create')
            ->once()
            ->andThrow(new BadResponseException('Test case', new Request('post', 'http://foo.bar')));

        $notification = new TestNotification(
            IntercomMessage::create('Hello World!')
                ->fromAdminId(123)
                ->toUserId(321)
        );

        $this->expectException(RequestException::class);

        $this->channel->send(new TestNotifiable(), $notification);
    }

    public function testItGetsToFromRouteNotificationForIntercomMethod(): void
    {
        $this->intercomMessages->shouldReceive('create');

        $message = IntercomMessage::create('Hello World!')
            ->fromAdminId(123);
        $notification = new TestNotification($message);

        $expected = ['type' => 'user', 'id' => 321];
        $this->channel->send(new TestNotifiable($expected), $notification);

        self::assertEquals($expected, $message->payload['to']);
    }
}
