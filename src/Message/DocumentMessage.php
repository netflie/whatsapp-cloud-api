<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\Media\MediaID;

final class DocumentMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'document';

    /**
    * Document identifier: WhatsApp Media ID or any Internet public link document.
    *
    * You can get a WhatsApp Media ID uploading the document to the WhatsApp Cloud servers.
    */
    private MediaID $id;

    /**
     * Describes the filename for the specific document: eg. my-document.pdf.
     */
    private string $name;

    /**
     * Describes the specified document.
     */
    private ?string $caption;

    /**
    * {@inheritdoc}
    */
    public function __construct(string $to, MediaID $id, string $name, ?string $caption)
    {
        $this->id = $id;
        $this->name = $name;
        $this->caption = $caption;

        parent::__construct($to);
    }

    /**
     * Name of the document to show on a WhatsApp message.
     */
    public function filename(): string
    {
        return $this->name;
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
