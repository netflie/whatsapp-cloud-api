<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestVideoMessage extends MessageRequest
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
            'type' => $this->message->type(),
            $this->message->type() => [
                $this->message->identifierType() => $this->message->identifierValue(),
                'caption' => $this->message->caption(),
            ],
        ];

        if ($this->message->replyTo()) {
            $body['context']['message_id'] = $this->message->replyTo();
        }

        return $body;
    }
}
