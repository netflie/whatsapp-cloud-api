<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification\Support\Products;

final class Order extends MessageNotification
{
    private string $catalog_id;
    private string $message;
    private Products $products;

    public function __construct(
        string $id,
        Support\Business $business,
        string $catalog_id,
        string $message,
        Products $products,
        string $received_at_timestamp
    ) {
        parent::__construct($id, $business, $received_at_timestamp);

        $this->catalog_id = $catalog_id;
        $this->message = $message;
        $this->products = $products;
    }

    public function catalogId(): string
    {
        return $this->catalog_id;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function products(): Products
    {
        return $this->products;
    }
}
