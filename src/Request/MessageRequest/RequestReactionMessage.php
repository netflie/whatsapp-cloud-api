<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestReactionMessage extends MessageRequest
{
    /**
     * {@inheritdoc}
     */
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body[$this->message->type()] = [
            'message_id' => $this->message->message_id(),
            'emoji' => $this->message->emoji(),
        ];

        return $body;
    }
}
