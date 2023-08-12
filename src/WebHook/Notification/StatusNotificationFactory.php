<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

class StatusNotificationFactory
{
    public function buildFromPayload(array $metadata, array $status): StatusNotification
    {
        $notification = new StatusNotification(
            $status['id'],
            new Support\Business($metadata['phone_number_id'], $metadata['display_phone_number']),
            $status['recipient_id'],
            $status['status'],
            $status['timestamp']
        );

        if (isset($status['conversation'])) {
            $notification->withConversation(new Support\Conversation(
                $status['conversation']['id'],
                $status['conversation']['origin']['type'],
                $status['conversation']['expiration_timestamp'] ?? null,
            ));
        }

        if (isset($status['pricing'])) {
            $notification->withPricing(new Support\Pricing(
                $status['pricing']['category'],
                $status['pricing']['pricing_model'],
                $status['pricing']['billable'],
            ));
        }

        if (isset($status['errors'])) {
            $notification->withError(new Support\Error(
                $status['errors'][0]['code'],
                $status['errors'][0]['title']
            ));
        }

        return $notification;
    }
}
