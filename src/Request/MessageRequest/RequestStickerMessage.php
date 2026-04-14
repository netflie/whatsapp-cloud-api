<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestStickerMessage extends MessageRequest
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
        ];

        return $body;
    }
}
