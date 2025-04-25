<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

class TemplateNotificationFactory
{
    public function buildFromPayload(array $payload, string $timestamp, int $id, ?string $field = null): TemplateNotification
    {
        $business = new Support\Business(
            '',
            ''
        );

        $notification = new TemplateNotification(
            $id,
            $business,
            $timestamp
        );

        if (isset($field) && $field === 'message_template_status_update') {
            $notification->withStatus(new Template\Status(
                $payload['event'] ?? '',
                $payload['message_template_id'] ?? '',
                $payload['message_template_name'] ?? '',
                $payload['message_template_language'] ?? '',
                $payload['reason'] ?? ''
            ));
        }

        return $notification;
    }
}
