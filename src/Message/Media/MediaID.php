<?php

namespace Netflie\WhatsAppCloudApi\Message\Media;

abstract class MediaID
{
    /**
     * Type of media identifier: id or link.
     */
    protected string $type;

    /**
     * Value of the identifier
     */
    private string $value;

    public function __construct(string $id)
    {
        $this->value = $id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->value;
    }
}
