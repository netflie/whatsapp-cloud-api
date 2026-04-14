<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\Error\InvalidMessage;

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
     * @var string|null WhatsApp ID or phone number for the person you want to send a message to.
     */
    private ?string $to;

    /**
     * @var string|null Business-scoped user id (BSUID) recipient.
     */
    private ?string $recipient;

    /**
     * The WhatsApp Message ID to reply to.
     */
    private ?string $reply_to = null;

    /**
     * Creates a new Message class.
     */
    public function __construct(?string $to, ?string $recipient, ?string $reply_to)
    {
        $this->to = $to;
        $this->recipient = $recipient;
        $this->reply_to = $reply_to;

        if (empty($this->to) && empty($this->recipient)) {
            throw new InvalidMessage('Either `to` or `recipient` is required.');
        }
    }

    /**
     * Return the WhatsApp ID or phone number for the person you want to send a message to.
     *
     * @return string|null
     */
    public function to(): ?string
    {
        return $this->to;
    }

    public function recipient(): ?string
    {
        return $this->recipient;
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
