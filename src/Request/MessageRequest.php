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
    protected $use_mm_lite;

    public function __construct(Message $message, string $access_token, string $from_phone_number_id, ?int $timeout = null, $use_mm_lite = false)
    {
        $this->message = $message;
        $this->from_phone_number_id = $from_phone_number_id;
        $this->use_mm_lite = $use_mm_lite;

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
        if ($this->use_mm_lite) {
            return $this->from_phone_number_id . '/marketing_messages';
        }
        return $this->from_phone_number_id . '/messages';
    }
}
