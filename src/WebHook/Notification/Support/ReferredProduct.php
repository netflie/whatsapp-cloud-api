<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class ReferredProduct
{
    private string $catalog_id;

    private string $product_retailer_id;

    public function __construct(string $catalog_id, string $product_retailer_id)
    {
        $this->catalog_id = $catalog_id;
        $this->product_retailer_id = $product_retailer_id;
    }

    public function catalogId(): string
    {
        return $this->catalog_id;
    }

    public function productRetailerId(): string
    {
        return $this->product_retailer_id;
    }
}
