<?php

namespace NotificationChannels\Intercom\Tests\Mocks;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    /**
     * @var bool|array
     */
    private $to;

    /**
     * @param  bool|array  $to
     */
    public function __construct($to = false)
    {
        $this->to = $to;
    }

    /**
     * @return array|bool
     */
    public function routeNotificationForIntercom()
    {
        return $this->to;
    }
}
