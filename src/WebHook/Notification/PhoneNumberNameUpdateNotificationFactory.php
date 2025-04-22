<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

class PhoneNumberNameUpdateNotificationFactory
{
    public function buildFromPayload(array $payload): PhoneNumberNameUpdateNotification
    {
        return new PhoneNumberNameUpdateNotification(
            $payload['display_phone_number'] ?? '',
            $payload['decision'] ?? '',
            $payload['requested_verified_name'] ?? '',
            $payload['rejection_reason'] ?? ''
        );
    }
}
