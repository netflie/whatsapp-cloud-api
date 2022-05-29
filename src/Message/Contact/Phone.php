<?php

namespace Netflie\WhatsAppCloudApi\Message\Contact;

class Phone
{
    protected string $number;

    protected PhoneType $type;

    public function __construct(string $number, PhoneType $type)
    {
        $this->number = $number;
        $this->type = $type;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function type(): PhoneType
    {
        return $this->type;
    }
}
