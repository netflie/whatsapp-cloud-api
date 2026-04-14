<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestLocationRequestMessage extends MessageRequest
{
    /**
     * {@inheritdoc}
     */
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = 'interactive';
        $body['interactive'] = [
            'type' => $this->message->type(),
            'body' => ['text' => $this->message->body()],
            'action' => [
                'name' => 'send_location',
            ],
        ];

        return $body;
    }
}
