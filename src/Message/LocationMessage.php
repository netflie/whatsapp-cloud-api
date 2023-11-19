<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\Error\InvalidMessage;

final class LocationMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'location';

    private float $longitude;

    private float $latitude;

    /**
     * Name of the location
     */
    private string $name;

    private string $address;

    /**
    * {@inheritdoc}
    */
    public function __construct(string $to, float $longitude, float $latitude, string $name = '', string $address = '', ?string $reply_to = null)
    {
        if ($address && !$name) {
            throw new InvalidMessage('Name is required.');
        }

        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->name = $name;
        $this->address = $address;

        parent::__construct($to, $reply_to);
    }

    public function longitude(): float
    {
        return $this->longitude;
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function address(): string
    {
        return $this->address;
    }
}
