<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestCtaUrlMessage extends MessageRequest
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
            'action' => $this->message->action(),
        ];

        if ($this->message->header()) {
            $body['interactive']['header'] = $this->message->header();
        }

        if ($this->message->body()) {
            $body['interactive']['body'] = ['text' => $this->message->body()];
        }

        if ($this->message->footer()) {
            $body['interactive']['footer'] = ['text' => $this->message->footer()];
        }

        return $body;
    }
}
