<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Request;

class RequestTemplateMessage extends Request
{
    /**
    * {@inheritdoc}
    */
    protected function makeBody(): void
    {
        $this->body = [
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
            $this->body['template']['components'][] = [
                'type' => 'header',
                'parameters' => $this->message->header(),
            ];
        }

        if ($this->message->body()) {
            $this->body['template']['components'][] = [
                'type' => 'body',
                'parameters' => $this->message->body(),
            ];
        }

        foreach ($this->message->buttons() as $button) {
            $this->body['template']['components'][] = $button;
        }
    }
}
