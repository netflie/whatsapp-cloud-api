<?php

namespace Netflie\WhatsAppCloudApi\Request\MessageRequest;

use Netflie\WhatsAppCloudApi\Message\ButtonReplyMessage;  
use Netflie\WhatsAppCloudApi\Request\MessageRequest;

class RequestButtonReplyMessage extends MessageRequest {

    public function body(): array {

        $body = [
          'messaging_product' => $this->message->messagingProduct(),
          'recipient_type' => $this->message->recipientType(),
          'to' => $this->message->to(),
          'type' => 'interactive',
          'interactive' => [
            'type' => 'button',
            'body' => ['text' => $this->message->body()],
            'action' => ['buttons' => $this->message->action()->buttons()]
          ]
        ];

        if($this->message->header()) {
          $body['interactive']['header'] = [
            'type' => 'text',
            'text' => $this->message->header()
          ];
        }

        if($this->message->footer()) {
          $body['interactive']['footer'] = [
            'type' => 'text',
            'text' => $this->message->footer()
          ]; 
        }
        
        return $body;
      
      }

}