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
        $body = [
            'messaging_product' => $this->message->messagingProduct(),
            'recipient_type' => $this->message->recipientType(),
            'to' => $this->message->to(),
            'type' => 'interactive',
            'interactive' => [
                'type' => $this->message->type(),
                'body' => ['text' => $this->message->body()],
                'action' => [
                    'name' => 'catalog_message',
                ],
            ],
        ];

        if ($this->message->thumbnail_product_retailer_id()) {
            $body['interactive']['action']['parameters'] = ['thumbnail_product_retailer_id' => $this->message->thumbnail_product_retailer_id()];
        }

        if ($this->message->footer()) {
            $body['interactive']['footer'] = ['text' => $this->message->footer()];
        }

        if ($this->message->replyTo()) {
            $body['context']['message_id'] = $this->message->replyTo();
        }

        return $body;
    }
}
