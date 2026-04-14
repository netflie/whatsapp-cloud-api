<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestCatalogMessage extends MessageRequest
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
            'body' => ['text' => $this->message->body()],
            'action' => [
                'name' => 'catalog_message',
            ],
        ];

        if ($this->message->thumbnail_product_retailer_id()) {
            $body['interactive']['action']['parameters'] = ['thumbnail_product_retailer_id' => $this->message->thumbnail_product_retailer_id()];
        }

        if ($this->message->footer()) {
            $body['interactive']['footer'] = ['text' => $this->message->footer()];
        }

        return $body;
    }
}
