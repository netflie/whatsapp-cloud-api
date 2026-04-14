<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestMultiProductMessage extends MessageRequest
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
            'header' => $this->message->header(),
            'body' => ['text' => $this->message->body()],
            'action' => [
                'catalog_id' => $this->message->catalog_id(),
                'sections' => $this->message->sections(),
            ],
        ];

        if ($this->message->footer()) {
            $body['interactive']['footer'] = ['text' => $this->message->footer()];
        }

        return $body;
    }
}
