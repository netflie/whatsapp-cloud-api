<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification;

final class PhoneNotification extends Notification
{
    private string $display_phone_number;
    private ?Phone\Name $name = null;

    public function __construct(
        string $id,
        Support\Business $business,
        string $display_phone_number,
        string $received_at
    ) {
        parent::__construct($id, $business, $received_at);
        $this->display_phone_number = $display_phone_number;
    }

    public function withName(Phone\Name $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function displayPhoneNumber(): string
    {
        return $this->display_phone_number;
    }

    public function displayName(): ?string
    {
        return $this->name?->displayPhoneNumber();
    }

    public function decision(): ?string
    {
        return $this->name?->decision();
    }

    public function verifiedName(): ?string
    {
        return $this->name?->requestedVerifiedName();
    }
}
