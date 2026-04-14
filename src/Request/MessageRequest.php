<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Message\Message;
use Netflie\WhatsAppCloudApi\Request;

abstract class MessageRequest extends Request implements RequestWithBody
{
    /**
     * @var Message WhatsApp Message to be sent.
     */
    protected Message $message;

    /**
     * @var string WhatsApp Number Id from messages will sent.
     */
    private string $from_phone_number_id;

    public function __construct(Message $message, string $access_token, string $from_phone_number_id, ?int $timeout = null)
    {
        $this->message = $message;
        $this->from_phone_number_id = $from_phone_number_id;

        parent::__construct($access_token, $timeout);
    }

    /**
     * Return WhatsApp Number Id for this request.
     *
     * @return string
     */
    public function fromPhoneNumberId(): string
    {
        return $this->from_phone_number_id;
    }

    /**
     * WhatsApp node path.
     *
     * @return string
     */
    public function nodePath(): string
    {
        return $this->from_phone_number_id . '/messages';
    }

    public function body(): array
    {
        return $this->build();
    }

    protected function build(): array
    {
        $body = [
            'messaging_product' => $this->message->messagingProduct(),
            'recipient_type' => $this->message->recipientType(),
        ];

        if (!empty($this->message->to())) {
            $body['to'] = $this->message->to();
        }

        if (!empty($this->message->recipient())) {
            $body['recipient'] = $this->message->recipient();
        }

        if ($this->message->replyTo()) {
            $body['context']['message_id'] = $this->message->replyTo();
        }

        return $body;
    }
}
