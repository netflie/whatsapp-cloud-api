<?php

namespace Netflie\WhatsAppCloudApi\Message;

final class CatalogMessage extends Message
{
    /**
     * {@inheritdoc}
     */
    protected string $type = 'catalog_message';

    private string $body;

    private ?string $footer;

    private ?string $thumbnail_product_retailer_id;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $to, string $body, ?string $footer, ?string $thumbnail_product_retailer_id, ?string $reply_to)
    {
        $this->body = $body;
        $this->footer = $footer;
        $this->thumbnail_product_retailer_id = $thumbnail_product_retailer_id;

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

    public function thumbnail_product_retailer_id(): ?string
    {
        return $this->thumbnail_product_retailer_id;
    }
}
