<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

final class Button extends MessageNotification
{
    private string $text;

    private string $payload;

    public function __construct(
        string $id,
        Support\Business $business,
        string $text,
        string $payload,
        string $received_at_timestamp
    ) {
        parent::__construct($id, $business, $received_at_timestamp);

        $this->text = $text;
        $this->payload = $payload;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function payload(): string
    {
        return $this->payload;
    }
}
