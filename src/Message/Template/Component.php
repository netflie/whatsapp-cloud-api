<?php

namespace Netflie\WhatsAppCloudApi\Message\Template;

final class Component
{
    /**
     * Parameters of a header template.
     */
    private array $header;

    /**
     * Parameters of a body template.
     */
    private array $body;

    /**
     * Buttons to attach to a template.
     */
    private array $buttons;

    private array $carousel;

    public function __construct(array $header = [], array $body = [], array $buttons = [], array $carousel = [])
    {
        $this->header = $header;
        $this->body = $body;
        $this->buttons = $buttons;
        $this->carousel = $carousel;
    }

    public function header(): array
    {
        return $this->header;
    }

    public function body(): array
    {
        return $this->body;
    }

    public function buttons(): array
    {
        return $this->buttons;
    }
    public function carousel(): array
    {
        return $this->carousel;
    }
}
