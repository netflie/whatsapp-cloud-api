<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Phone;

final class Name
{
    private string $display_phone_number;
    private string $decision;
    private string $requested_verified_name;

    public function __construct(
        string $display_phone_number,
        string $decision,
        string $requested_verified_name
    ) {
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
