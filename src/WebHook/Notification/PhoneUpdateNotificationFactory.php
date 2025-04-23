<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

class PhoneUpdateNotificationFactory
{
    public function buildFromPayload(array $payload, string $timestamp, int $id, ?string $field = null): PhoneUpdateNotification
    {
        $business = new Support\Business(
            '',
            $payload['display_phone_number'] ?? ''
        );

        $phoneUpdate = new PhoneUpdateNotification(
            $id ?? '',
            $business,
            $payload['display_phone_number'] ?? '',
            $timestamp
        );

        if (isset($field) && $field === 'phone_number_name_update') {
            $phoneUpdate->displayName(new Phone\Name(
                $payload['display_phone_number'] ?? '',
                $payload['decision'] ?? '',
                $payload['requested_verified_name'] ?? '',
            ));
        }

        return $phoneUpdate;
    }
}
