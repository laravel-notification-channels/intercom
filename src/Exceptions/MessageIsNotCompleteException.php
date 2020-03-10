<?php

namespace NotificationChannels\Intercom\Exceptions;

use NotificationChannels\Intercom\IntercomMessage;
use Throwable;

class MessageIsNotCompleteException extends IntercomException
{
    /**
     * @var IntercomMessage
     */
    private $intercomMessage;

    /**
     * @param IntercomMessage $intercomMessage
     * @param string          $message
     * @param int             $code
     * @param Throwable|null  $previous
     */
    public function __construct(
        IntercomMessage $intercomMessage,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->intercomMessage = $intercomMessage;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return IntercomMessage
     */
    public function getIntercomMessage(): IntercomMessage
    {
        return $this->intercomMessage;
    }
}
