<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Request;

class RequestDocumentMessage extends Request
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
            'document' => [
                'caption' => $this->message->caption(),
                'filename' => $this->message->filename(),
                $this->message->identifierType() => $this->message->identifierValue(),
            ],
        ];
    }
}
