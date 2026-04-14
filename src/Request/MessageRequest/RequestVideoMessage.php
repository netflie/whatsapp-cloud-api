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
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body[$this->message->type()] = [
            $this->message->identifierType() => $this->message->identifierValue(),
            'caption' => $this->message->caption(),
        ];

        return $body;
    }
}
