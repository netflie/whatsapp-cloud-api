<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

class PhoneNumberNameUpdateNotificationFactory
{
    public function buildFromPayload(array $payload, string $timestamp, int $id): PhoneNumberNameUpdateNotification
    {
        $business = new Support\Business(
            '',
            $payload['display_phone_number'] ?? ''
        );

        return new PhoneNumberNameUpdateNotification(
            $id ?? '',
            $business,
            $payload['display_phone_number'] ?? '',
            $payload['decision'] ?? '',
            $payload['requested_verified_name'] ?? '',
            $timestamp
        );
    }
}
