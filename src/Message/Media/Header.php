<?php

namespace Netflie\WhatsAppCloudApi\Message\Media;

class Header
{
    /**
     * @var string The type of the header
     */
    private $type;

    private $payload;

    public function __construct(string $type, array $payload)
    {
        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * Get the type of the header
     *
     * @return string The type of the header
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the payload of the header.
     *
     * @return string|array The payload of the header.
     */
    public function payload()
    {
        if ($this->type === "text") {
            return $this->payload['text'];
        }

        return $this->payload;
    }
}
