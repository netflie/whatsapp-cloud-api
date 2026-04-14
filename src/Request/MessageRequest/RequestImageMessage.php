<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestImageMessage extends MessageRequest
{
    /**
    * {@inheritdoc}
    */
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body['image'] = [
            'caption' => $this->message->caption(),
            $this->message->identifierType() => $this->message->identifierValue(),
        ];

        return $body;
    }
}
