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
        $body = [
            'messaging_product' => $this->message->messagingProduct(),
            'recipient_type' => $this->message->recipientType(),
            'to' => $this->message->to(),
            'type' => 'interactive',
            'interactive' => [
                'type' => $this->message->type(),
                'body' => ['text' => $this->message->body()],
                'action' => [
                    'name' => 'send_location',
                ],
            ],
        ];

        if ($this->message->replyTo()) {
            $body['context']['message_id'] = $this->message->replyTo();
        }

        return $body;
    }
}
