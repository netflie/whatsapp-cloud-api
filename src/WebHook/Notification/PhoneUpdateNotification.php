<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification;

final class PhoneUpdateNotification extends Notification
{
    private string $display_phone_number;
    private string $decision;
    private string $requested_verified_name;

    public function __construct(
        string $id,
        Support\Business $business,
        string $display_phone_number,
        string $received_at
    ) {
        parent::__construct($id, $business, $received_at);

        $this->display_phone_number = $display_phone_number;
    }

    public function displayName(Phone\Name $name): self
    {
        $this->display_phone_number = $name->displayPhoneNumber();
        $this->decision = $name->decision();
        $this->requested_verified_name = $name->requestedVerifiedName();

        return $this;
    }
}
