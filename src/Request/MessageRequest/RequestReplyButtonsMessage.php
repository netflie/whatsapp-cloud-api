<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Message\ReplyButtonsMessage;
use Netflie\WhatsAppCloudApi\Request\MessageRequest;

final class RequestReplyButtonsMessage extends MessageRequest
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
                'body' => ['text' => $this->message->text()],
                'action' => [$this->message->action()],
            ],
        ];

        return $request;
    }
}
