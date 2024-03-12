<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

class ButtonHeader
{
    /**
     * @var string The type of the button header
     */
    private $type;

    private $payload;

    private const ALLOWED_TYPES = ['text', 'image', 'video', 'document'];

    public function __construct(string $type, array $payload)
    {
        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new \InvalidArgumentException('Invalid header type. Allowed types are: ' . implode(', ', self::ALLOWED_TYPES));
        }

        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * Get the type of the button header
     *
     * @return string The type of the button header
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the content of the button header
     *
     * @return string The content of the button header
     */
    public function payload(): string|array
    {
        if ($this->type === "text") {
            return $this->payload['text'];
        }

        return $this->payload;
    }
}
