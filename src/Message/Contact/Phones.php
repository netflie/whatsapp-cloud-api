<?php

namespace Netflie\WhatsAppCloudApi\Message\Contact;

final class Phones implements \Countable, \IteratorAggregate
{
    private array $phones;

    public function __construct(Phone ...$phones)
    {
        $this->phones = $phones;
    }

    public function count(): int
    {
        return count($this->phones);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->phones);
    }
}
