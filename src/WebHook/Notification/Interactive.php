<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

final class Interactive extends MessageNotification
{
    private string $item_id;

    private string $title;

    private string $description;

    public function __construct(
        string $id,
        Support\Business $business,
        string $item_id,
        string $title,
        string $description,
        string $received_at_timestamp
    ) {
        parent::__construct($id, $business, $received_at_timestamp);

        $this->item_id = $item_id;
        $this->title = $title;
        $this->description = $description;
    }

    public function itemId(): string
    {
        return $this->item_id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }
}
