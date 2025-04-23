<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification;

final class PhoneNumberNameUpdateNotification extends Notification
{
    private string $display_phone_number;
    private string $decision;
    private string $requested_verified_name;

    public function __construct(
        string $id,
        Support\Business $business,
        string $display_phone_number,
        string $decision,
        string $requested_verified_name,
        string $received_at
    ) {
        parent::__construct($id, $business, $received_at);

        $this->display_phone_number = $display_phone_number;
        $this->decision = $decision;
        $this->requested_verified_name = $requested_verified_name;
    }

    public function displayPhoneNumber(): string
    {
        return $this->display_phone_number;
    }

    public function decision(): string
    {
        return $this->decision;
    }

    public function requestedVerifiedName(): string
    {
        return $this->requested_verified_name;
    }
}
