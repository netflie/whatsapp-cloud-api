<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

class StatusNotificationFactory
{
    public function buildFromPayload(array $metadata, array $status): StatusNotification
    {
        // BSUID support: recipient_id may be omitted for username-enabled users.
        // Fall back to recipient_user_id when phone-based recipient_id is missing.
        $recipientId = $status['recipient_id'] ?? null;
        $recipientUserId = $status['recipient_user_id'] ?? null;
        $parentRecipientUserId = $status['parent_recipient_user_id'] ?? null;

        $notification = new StatusNotification(
            $status['id'],
            new Support\Business($metadata['phone_number_id'], $metadata['display_phone_number']),
            $recipientId,
            $status['status'],
            $status['timestamp'],
            $recipientUserId,
            $parentRecipientUserId
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
