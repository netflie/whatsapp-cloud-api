<?php

namespace Netflie\WhatsAppCloudApi\Message;

final class ReactionMessage extends Message
{
    /**
     * {@inheritdoc}
     */
    protected string $type = 'reaction';

    private $emoji;

    private $message_id;

    /**
     * {@inheritdoc}
     */
    public function __construct(?string $to, string $message_id, string $emoji, ?string $recipient = null)
    {
        $this->emoji = $emoji;
        $this->message_id = $message_id;

        parent::__construct($to, $recipient, null);
    }

    public function emoji(): string
    {
        return $this->emoji;
    }

    public function message_id(): string
    {
        return $this->message_id;
    }
}
