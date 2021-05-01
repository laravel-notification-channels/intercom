<?php

namespace NotificationChannels\Intercom;

final class IntercomMessage
{
    public const TYPE_EMAIL = 'email';

    public const TYPE_INAPP = 'inapp';

    public const TEMPLATE_PLAIN = 'plain';

    public const TEMPLATE_PERSONAL = 'personal';

    /**
     * @param string $body
     *
     * @return IntercomMessage
     */
    public static function create(?string $body = null): self
    {
        return new static($body);
    }

    /**
     * @var array
     */
    public $payload = [];

    /**
     * @var string|null
     */
    public $conversationId = null;

    /**
     * @param string|null $body
     */
    public function __construct(?string $body = null)
    {
        if (null !== $body) {
            $this->body($body);
        }

        $this->inapp();
    }

    /**
     * @param string $body
     *
     * @return IntercomMessage
     */
    public function body(string $body): self
    {
        $this->payload['body'] = $body;

        return $this;
    }

    /**
     * @return IntercomMessage
     */
    public function email(): self
    {
        $this->payload['message_type'] = self::TYPE_EMAIL;

        return $this;
    }

    /**
     * @return IntercomMessage
     */
    public function inapp(): self
    {
        $this->payload['message_type'] = self::TYPE_INAPP;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return IntercomMessage
     */
    public function subject(string $value): self
    {
        if (!empty($value)) {
            $this->payload['subject'] = $value;
        }

        return $this;
    }

    /**
     * @return IntercomMessage
     */
    public function plain(): self
    {
        $this->payload['template'] = self::TEMPLATE_PLAIN;

        return $this;
    }

    /**
     * @return IntercomMessage
     */
    public function personal(): self
    {
        $this->payload['template'] = self::TEMPLATE_PERSONAL;

        return $this;
    }

    /**
     * @param array $value
     *
     * @return IntercomMessage
     */
    public function from(array $value): self
    {
        $this->payload['from'] = $value;

        return $this;
    }

    /**
     * @param string $adminId
     *
     * @return IntercomMessage
     */
    public function fromAdminId(string $adminId): self
    {
        return $this->from([
            'type' => 'admin',
            'id' => $adminId,
        ]);
    }

    /**
     * @param string $userId
     *
     * @return IntercomMessage
     */
    public function fromUserId(string $userId): self
    {
        return $this->from([
            'type' => 'user',
            'id' => $userId,
        ]);
    }

    /**
     * @param string $userEmail
     *
     * @return IntercomMessage
     */
    public function fromUserEmail(string $userEmail): self
    {
        return $this->from([
            'type' => 'user',
            'email' => $userEmail,
        ]);
    }

    /**
     * @param array $value
     *
     * @return IntercomMessage
     */
    public function to(array $value): self
    {
        $this->payload['to'] = $value;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return IntercomMessage
     */
    public function toUserId(string $id): self
    {
        return $this->to([
            'type' => 'user',
            'id' => $id,
        ]);
    }

    /**
     * @param string $email
     *
     * @return IntercomMessage
     */
    public function toUserEmail(string $email): self
    {
        return $this->to([
            'type' => 'user',
            'email' => $email,
        ]);
    }

    /**
     * @param string $id
     *
     * @return IntercomMessage
     */
    public function toContactId(string $id): self
    {
        return $this->to([
            'type' => 'contact',
            'id' => $id,
        ]);
    }

    /**
     * @param string $id
     *
     * @return IntercomMessage
     */
    public function toConversationId(string $id): self
    {
        if (!empty($id)) {
            $this->conversationId = $id;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return isset(
            $this->payload['body'],
            $this->payload['from'],
            $this->payload['to']
        );
    }

    /**
     * @return bool
     */
    public function hasRecipient(): bool
    {
        return isset($this->payload['to']);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->payload;
    }

    public function getConversationBody(): array
    {
        $body = [
            'message_type' => 'comment',
            'body' => $this->payload['body'] ?? null,
        ];

        if ($this->isValid()) {
            $body['type'] = $this->payload['to']['type'];

            if ($body['type'] === 'admin') {
                $body['admin_id'] = $this->payload['to']['type'];
            } else {
                if (!empty($this->payload['to']['email'])) {
                    $body['email'] = $this->payload['to']['email'];
                } else {
                    $body['user_id'] = $this->payload['to']['id'] ?? null;
                }
            }
        }

        return $body;
    }
}
