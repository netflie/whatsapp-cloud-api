<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\Contact\ContactName;
use Netflie\WhatsAppCloudApi\Message\Contact\Phone;
use Netflie\WhatsAppCloudApi\Message\Contact\Phones;

final class ContactMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'contacts';

    private ContactName $name;

    private Phones $phones;

    /**
    * {@inheritdoc}
    */
    public function __construct(string $to, ContactName $name, Phone ...$phones)
    {
        $this->name = $name;
        $this->phones = new Phones(...$phones);

        parent::__construct($to);
    }

    public function fullName(): string
    {
        return $this->name->fullName();
    }

    public function firstName(): string
    {
        return $this->name->firstName();
    }

    public function lastName(): string
    {
        return $this->name->lastName();
    }

    public function phones(): Phones
    {
        return $this->phones;
    }
}
