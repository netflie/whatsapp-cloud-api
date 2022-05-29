<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Request;

class RequestLocationMessage extends Request
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
            $this->message->type() => [
                'longitude' => $this->message->longitude(),
                'latitude' => $this->message->latitude(),
                'name' => $this->message->name(),
                'address' => $this->message->address(),
            ],
        ];
    }
}
