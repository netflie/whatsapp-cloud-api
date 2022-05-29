<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\Media\MediaID;

class ImageMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'image';

    /**
    * Document identifier: WhatsApp Media ID or any Internet public link document.
    *
    * You can get a WhatsApp Media ID uploading the document to the WhatsApp Cloud servers.
    */
    protected MediaID $id;

    /**
     * Describes the specified document.
     */
    protected ?string $caption;

    /**
    * {@inheritdoc}
    */
    public function __construct(string $to, MediaID $id, ?string $caption = '')
    {
        $this->id = $id;
        $this->caption = $caption;

        parent::__construct($to);
    }

    public function caption(): ?string
    {
        return $this->caption;
    }

    public function identifierType(): string
    {
        return $this->id->type();
    }

    public function identifierValue(): string
    {
        return $this->id->value();
    }
}
