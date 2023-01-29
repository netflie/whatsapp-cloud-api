<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Products extends \ArrayIterator
{
    public function productRetailerId(): string
    {
        return $this->current()['product_retailer_id'];
    }

    public function quantity(): string
    {
        return $this->current()['quantity'];
    }

    public function price(): string
    {
        return $this->current()['item_price'];
    }

    public function currency(): string
    {
        return $this->current()['currency'];
    }

    public function hasProductsToIterate(): bool
    {
        return $this->valid();
    }

    public function nextProduct(): self
    {
        $this->next();

        return $this;
    }
}
