<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Request;

class RequestContactMessage extends Request
{
    /**
     * Makes the raw body of the request.
     *
     */
    protected function makeBody(): void
    {
        $message_type = $this->message->type();

        $this->body = [
            'messaging_product' => $this->message->messagingProduct(),
            'recipient_type' => $this->message->recipientType(),
            'to' => $this->message->to(),
            'type' => $this->message->type(),
            $message_type => [
                [
                    'name' => [
                        'formatted_name' => $this->message->fullName(),
                        'first_name' => $this->message->firstName(),
                        'last_name' => $this->message->lastName(),
                    ],
                ],
            ],
        ];

        foreach ($this->message->phones() as $phone) {
            $phone_array = [
                'phone' => $phone->number(),
                'type' => $phone->type()->getValue(),
            ];

            if (!empty($phone->waId())) {
                $phone_array['wa_id'] = $phone->waId();
            }

            $this->body[$message_type][0]['phones'][] = $phone_array;
        }
    }
}
