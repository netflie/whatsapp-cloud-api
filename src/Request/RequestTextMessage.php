<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Request;

class RequestTextMessage extends Request
{
    /**
     * Makes the raw body of the request.
     *
     */
    protected function makeBody(): void
    {
        $this->body = [
            'messaging_product' => $this->message->messagingProduct(),
            'recipient_type' => $this->message->recipientType(),
            'to' => $this->message->to(),
            'type' => $this->message->type(),
            'text' => [
                'preview_url' => $this->message->previewUrl(),
                'body' => $this->message->text(),
            ],
        ];
    }
}
