<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification\Support\Products;

final class Order extends MessageNotification
{
    private string $catalog_id;
    private Products $products;

    public function __construct(
        string $id,
        Support\Business $business,
        string $catalog_id,
        Products $products,
        string $received_at_timestamp
    ) {
        parent::__construct($id, $business, $received_at_timestamp);

        $this->catalog_id = $catalog_id;
        $this->products = $products;
    }

    public function catalogId(): string
    {
        return $this->catalog_id;
    }

    public function products(): Products
    {
        return $this->products;
    }
}
