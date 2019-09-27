<?php

namespace NotificationChannels\Intercom\Tests\Mocks;

use Illuminate\Notifications\Notification;
use NotificationChannels\Intercom\IntercomMessage;

class TestNotification extends Notification
{
    /**
     * @var IntercomMessage
     */
    private $intercomMessage;

    /**
     * @param IntercomMessage $intercomMessage
     */
    public function __construct(IntercomMessage $intercomMessage)
    {
        $this->intercomMessage = $intercomMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function toIntercom($notifiable): IntercomMessage
    {
        return $this->intercomMessage;
    }
}
