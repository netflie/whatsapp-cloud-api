<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestOptionsListMessage extends MessageRequest
{
    /**
    * {@inheritdoc}
    */
    public function body(): array
    {
        $request = [
            'messaging_product' => $this->message->messagingProduct(),
            'recipient_type' => $this->message->recipientType(),
            'to' => $this->message->to(),
            'type' => 'interactive',
            'interactive' => [
                'type' => $this->message->type(),
                'header' => [
                    'type' => 'text',
                    'text' => $this->message->header(),
                ],
                'body' => ['text' => $this->message->body()],
                'action' => $this->message->action(),
            ],
        ];

        if ($this->message->footer()) {
            $request['interactive']['footer'] = ['text' => $this->message->footer()];
        }

        return $request;
    }
}
