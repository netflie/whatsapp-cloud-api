<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestDocumentMessage extends MessageRequest
{
    /**
    * {@inheritdoc}
    */
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body['document'] = [
            'caption' => $this->message->caption(),
            'filename' => $this->message->filename(),
            $this->message->identifierType() => $this->message->identifierValue(),
        ];

        return $body;
    }
}
