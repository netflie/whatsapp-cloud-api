<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

final class Flow extends MessageNotification
{
    private string $name;

    private string $body;

    private string $response;

    public function __construct(
        string $id,
        Support\Business $business,
        string $name,
        string $body,
        string $response,
        string $received_at_timestamp
    ) {
        parent::__construct($id, $business, $received_at_timestamp);

        $this->name = $name;
        $this->body = $body;
        $this->response = $response;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function response(): string
    {
        return $this->response;
    }
}
