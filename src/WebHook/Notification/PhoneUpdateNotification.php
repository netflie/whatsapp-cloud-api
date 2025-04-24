<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification;

final class PhoneUpdateNotification extends Notification
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

    public function name(Phone\Name $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function displayName(): string
    {
        if (!$this->name) {
            return null;
        }

        return $this->name->displayPhoneNumber();
    }

    public function decision(): string
    {
        if (!$this->name) {
            return null;
        }

        return $this->name->decision();
    }

    public function verifiedName(): string
    {
        if (!$this->name) {
            return null;
        }

        return $this->name->requestedVerifiedName();
    }
}
