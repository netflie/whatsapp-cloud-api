<?php

namespace Netflie\WhatsAppCloudApi\Message;

final class CatalogMessage extends Message
{
    /**
     * {@inheritdoc}
     */
    protected string $type = 'catalog_message';

    private string $body;

    private string $footer;

    private string $thumbnail;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $to, string $body, ?string $footer, ?string $thumbnail, ?string $reply_to)
    {
        $this->body = $body;
        $this->footer = $footer;
        $this->thumbnail = $thumbnail;

        parent::__construct($to, $reply_to);
    }

    public function body(): string
    {
        return $this->body;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }

    public function productRetailerId(): ?string
    {
        return $this->thumbnail;
    }
}
