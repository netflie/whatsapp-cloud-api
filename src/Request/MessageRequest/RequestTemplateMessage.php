<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestTemplateMessage extends MessageRequest
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
            'type' => $this->message->type(),
            'template' => [
                'name' => $this->message->name(),
                'language' => ['code' => $this->message->language()],
                'components' => [],
            ],
        ];

        if ($this->message->header()) {
            $body['template']['components'][] = [
                'type' => 'header',
                'parameters' => $this->message->header(),
            ];
        }

        if ($this->message->body()) {
            $body['template']['components'][] = [
                'type' => 'body',
                'parameters' => $this->message->body(),
            ];
        }

        foreach ($this->message->buttons() as $button) {
            $body['template']['components'][] = $button;
        }

        return $body;
    }
}
