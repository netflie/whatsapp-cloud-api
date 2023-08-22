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
        $notifications = $this->buildAllFromPayload($payload);

        return $notifications[0] ?? null;
    }

    /**
     * @return Notification[]
     */
    public function buildAllFromPayload(array $payload): array
    {
        if (!is_array($payload['entry'] ?? null)) {
            return [];
        }

        $notifications = [];

        foreach ($payload['entry'] as $entry) {
            if (!is_array($entry['changes'])) {
                continue;
            }

            foreach ($entry['changes'] as $change) {
                $message = $change['value']['messages'][0] ?? [];
                $status = $change['value']['statuses'][0] ?? [];
                $contact = $change['value']['contacts'][0] ?? [];
                $metadata = $change['value']['metadata'] ?? [];

                if ($message) {
                    $notifications[] = $this->message_notification_factory->buildFromPayload($metadata, $message, $contact);
                }

                if ($status) {
                    $notifications[] = $this->status_notification_factory->buildFromPayload($metadata, $status);
                }
            }
        }

        return $notifications;
    }
}
