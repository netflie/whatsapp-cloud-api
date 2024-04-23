<?php

namespace Netflie\WhatsAppCloudApi\Message;

final class SingleProductMessage extends Message
{
    /**
     * {@inheritdoc}
     */
    protected string $type = 'product';

    private int $catalog_id;

    private string $product_retailer_id;

    private ?string $body;

    private ?string $footer;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $to, int $catalog_id, string $product_retailer_id, ?string $body, ?string $footer, ?string $reply_to)
    {
        $this->catalog_id = $catalog_id;
        $this->product_retailer_id = $product_retailer_id;
        $this->body = $body;
        $this->footer = $footer;

        parent::__construct($to, $reply_to);
    }

    public function catalog_id(): int
    {
        return $this->catalog_id;
    }

    public function product_retailer_id(): string
    {
        return $this->product_retailer_id;
    }

    public function body(): ?string
    {
        return $this->body;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }
}
