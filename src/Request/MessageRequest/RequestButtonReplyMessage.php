<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

class RequestButtonReplyMessage extends MessageRequest
{
    public function body(): array
    {
        $body = parent::body();
        $body['type'] = 'interactive';
        $body['interactive'] = [
            'type' => 'button',
            'body' => ['text' => $this->message->body()],
            'action' => ['buttons' => $this->message->action()->buttons()],
        ];

        if ($this->message->header()) {
            $body['interactive']['header'] = [
                'type' => 'text',
                'text' => $this->message->header(),
            ];
        }

        if ($this->message->footer()) {
            $body['interactive']['footer'] = [
                'text' => $this->message->footer(),
            ];
        }

        return $body;
    }
}
