<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

class PhoneNotificationFactory
{
    public function buildFromPayload(array $payload, string $timestamp, int $id, ?string $field = null): PhoneNotification
    {
        $business = new Support\Business(
            '',
            $payload['display_phone_number'] ?? ''
        );

        $notification = new PhoneNotification(
            $id,
            $business,
            $payload['display_phone_number'] ?? '',
            $timestamp
        );

        if (isset($field) && $field === 'phone_number_name_update') {
            $notification->withName(new Phone\Name(
                $payload['display_phone_number'] ?? '',
                $payload['decision'] ?? '',
                $payload['requested_verified_name'] ?? ''
            ));
        }

        return $notification;
    }
}
