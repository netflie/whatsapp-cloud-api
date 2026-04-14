<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestLocationMessage extends MessageRequest
{
    /**
    * {@inheritdoc}
    */
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body[$this->message->type()] = [
            'longitude' => $this->message->longitude(),
            'latitude' => $this->message->latitude(),
            'name' => $this->message->name(),
            'address' => $this->message->address(),
        ];

        return $body;
    }
}
