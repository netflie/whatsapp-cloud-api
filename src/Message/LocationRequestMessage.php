<?php

namespace Netflie\WhatsAppCloudApi\Message;

final class LocationRequestMessage extends Message
{
    /**
     * {@inheritdoc}
     */
    protected string $type = 'location_request_message';

    private string $body;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $to, string $body, ?string $reply_to)
    {
        $this->body = $body;

        parent::__construct($to, $reply_to);
    }

    public function body(): string
    {
        return $this->body;
    }
}
