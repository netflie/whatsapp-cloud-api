<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Message\OptionsListMessage;
use Netflie\WhatsAppCloudApi\Request\MessageRequest;

/**
 * @property OptionsListMessage $message
 */
class RequestOptionsListMessage extends MessageRequest
{
    /**
    * {@inheritdoc}
    */
    public function body(): array
    {
        return [
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
                'footer' => ['text' => $this->message->footer()],
                'action' => $this->message->action(),
            ],
        ];
    }
}
