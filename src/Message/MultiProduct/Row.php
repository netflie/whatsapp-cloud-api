<?php

namespace Netflie\WhatsAppCloudApi\Message\MultiProduct;

class Row
{
    protected string $product_retailer_id;

    public function __construct(string $product_retailer_id)
    {
        $this->product_retailer_id = $product_retailer_id;
    }

    public function productRetailerId(): string
    {
        return $this->product_retailer_id;
    }
}
