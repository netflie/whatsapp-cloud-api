<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

class ButtonHeader
{
    /**
     * @var string The type of the button header
     */
    private $type;

    /**
     * @var string The content of the button header
     */
    private $content;

    private $filename;

    private const ALLOWED_TYPES = ['text', 'image', 'video', 'document'];

    public function __construct(string $type, string $content, ?string $filename = null)
    {
        if (!in_array($type, self::ALLOWED_TYPES)) {
            throw new \InvalidArgumentException('Invalid header type. Allowed types are: ' . implode(', ', self::ALLOWED_TYPES));
        }

        $this->type = $type;
        $this->filename = $filename;
        $this->content = $content;
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
    public function content(): string
    {
        return $this->content;
    }

    public function filename(): ?string
    {
        return $this->filename;
    }
}
