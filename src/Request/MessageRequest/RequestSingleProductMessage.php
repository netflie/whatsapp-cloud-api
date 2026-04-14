<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestSingleProductMessage extends MessageRequest
{
    /**
     * {@inheritdoc}
     */
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = 'interactive';
        $body['interactive'] = [
            'type' => $this->message->type(),
            'action' => [
                'catalog_id' => $this->message->catalog_id(),
                'product_retailer_id' => $this->message->product_retailer_id(),
            ],
        ];

        if ($this->message->body()) {
            $body['interactive']['body'] = ['text' => $this->message->body()];
        }

        if ($this->message->footer()) {
            $body['interactive']['footer'] = ['text' => $this->message->footer()];
        }

        return $body;
    }
}
