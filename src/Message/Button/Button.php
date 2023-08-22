<?php

namespace Netflie\WhatsAppCloudApi\Message\Button;

class Button
{
    protected string $id;

    protected string $title;

    protected string $type;

    public function __construct(string $id, string $title, string $type = 'reply')
    {
        $this->id = $id;
        $this->title = $title;
        $this->type = $type;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function type(): string
    {
        return $this->type;
    }
}
