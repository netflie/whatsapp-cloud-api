<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

final class Reaction extends MessageNotification
{
    private string $message_id;

    private string $emoji;

    public function __construct(
        string $id,
        Support\Business $business,
        string $message_id,
        string $emoji,
        string $received_at_timestamp
    ) {
        parent::__construct($id, $business, $received_at_timestamp);

        $this->message_id = $message_id;
        $this->emoji = $emoji;
    }

    public function messageId(): string
    {
        return $this->message_id;
    }

    public function emoji(): string
    {
        return $this->emoji;
    }
}
