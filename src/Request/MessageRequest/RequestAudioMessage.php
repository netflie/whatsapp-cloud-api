<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestAudioMessage extends MessageRequest
{
    /**
    * {@inheritdoc}
    */
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body['audio'] = [
            $this->message->identifierType() => $this->message->identifierValue(),
        ];

        return $body;
    }
}
