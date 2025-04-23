<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Business
{
    private string $phone_number_id;

    private string $phone_number;
    private int $id = 0;

    public function __construct(string $phone_number_id, string $phone_number, int $id = 0)
    {
        $this->phone_number_id = $phone_number_id;
        $this->phone_number = $phone_number;
        $this->id = $id;
    }

    public function phoneNumberId(): string
    {
        return $this->phone_number_id;
    }

    public function phoneNumber(): string
    {
        return $this->phone_number;
    }

    public function id(): int
    {
        return $this->id;
    }
}
