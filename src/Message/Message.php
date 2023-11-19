<?php

namespace Netflie\WhatsAppCloudApi\Message;

abstract class Message
{
    /**
     * @var string Type of message object.
     */
    protected string $type;

    /**
     * @var string Currently only "whatsapp" value is supported.
     */
    private string $messaging_product = 'whatsapp';

    /**
     * @var string Currently only "individual" value is supported.
     */
    private string $recipient_type = 'individual';

    /**
     * @var string WhatsApp ID or phone number for the person you want to send a message to.
     */
    private string $to;

    /**
     * The WhatsApp Message ID to reply to.
     */
    private ?string $reply_to = null;

    /**
     * Creates a new Message class.
     */
    public function __construct(string $to, ?string $reply_to)
    {
        $this->to = $to;
        $this->reply_to = $reply_to;
    }

    /**
     * Return the WhatsApp ID or phone number for the person you want to send a message to.
     *
     * @return string
     */
    public function to(): string
    {
        return $this->to;
    }

    /**
     * Return the type of message object.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Return the messaging product.
     *
     * @return string
     */
    public function messagingProduct(): string
    {
        return $this->messaging_product;
    }

    /**
     * Return the recipient type.
     *
     * @return string
     */
    public function recipientType(): string
    {
        return $this->recipient_type;
    }

    /**
     * Return the WhatsApp Message ID to reply to.
     */
    public function replyTo(): ?string
    {
        return $this->reply_to;
    }
}
