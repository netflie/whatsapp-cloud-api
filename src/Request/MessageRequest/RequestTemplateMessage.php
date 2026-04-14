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
        $body = parent::body();
        $body['type'] = $this->message->type();
        $body['template'] = [
            'name' => $this->message->name(),
            'language' => ['code' => $this->message->language()],
            'components' => [],
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
