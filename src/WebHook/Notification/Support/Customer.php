<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Customer
{
    private string $id;

    private string $name;

    private string $phone_number;

    public function __construct(string $id, string $name, string $phone_number)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone_number = $phone_number;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function phoneNumber(): string
    {
        return $this->phone_number;
    }
}
