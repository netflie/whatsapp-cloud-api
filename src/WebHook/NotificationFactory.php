<?php

namespace Netflie\WhatsAppCloudApi\WebHook;

final class NotificationFactory
{
    private Notification\MessageNotificationFactory $message_notification_factory;
    private Notification\StatusNotificationFactory $status_notification_factory;

    public function __construct()
    {
        $this->message_notification_factory = new Notification\MessageNotificationFactory();
        $this->status_notification_factory = new Notification\StatusNotificationFactory();
    }

    public function buildFromPayload(array $payload): ?Notification
    {
        if (!is_array($payload['entry'] ?? null)) {
            return null;
        }

        $entry = $payload['entry'][0] ?? [];
        $message = $entry['changes'][0]['value']['messages'][0] ?? [];
        $status = $entry['changes'][0]['value']['statuses'][0] ?? [];
        $contact = $entry['changes'][0]['value']['contacts'][0] ?? [];
        $metadata = $entry['changes'][0]['value']['metadata'] ?? [];

        if ($message) {
            return $this->message_notification_factory->buildFromPayload($metadata, $message, $contact);
        }

        if ($status) {
            return $this->status_notification_factory->buildFromPayload($metadata, $status);
        }

        return null;
    }
}
