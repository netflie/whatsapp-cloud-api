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
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body['text'] = [
            'preview_url' => $this->message->previewUrl(),
            'body' => $this->message->text(),
        ];

        return $body;
    }
}
