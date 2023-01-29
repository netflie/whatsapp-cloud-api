<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

final class Unknown extends MessageNotification
{
    public function __construct(string $id, Support\Business $business, string $received_at_timestamp)
    {
        parent::__construct($id, $business, $received_at_timestamp);
    }
}
