<?php

namespace Netflie\WhatsAppCloudApi\Message\Contact;

final class ContactName
{
    private string $first_name;

    private string $last_name;

    public function __construct(string $first_name, string $last_name = '')
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
    }

    public function fullName(): string
    {
        return "$this->first_name $this->last_name";
    }

    public function firstName(): string
    {
        return $this->first_name;
    }

    public function lastName(): string
    {
        return $this->last_name;
    }
}
