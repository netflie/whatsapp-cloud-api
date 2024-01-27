<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestTextMessage extends MessageRequest
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
            'text' => [
                'preview_url' => $this->message->previewUrl(),
                'body' => $this->message->text(),
            ],
        ];

        if ($this->message->replyTo()) {
            $body['context']['message_id'] = $this->message->replyTo();
        }

        return $body;
    }
}
