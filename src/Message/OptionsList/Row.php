<?php

namespace Netflie\WhatsAppCloudApi\Message\OptionsList;

class Row
{
    protected string $id;

    protected string $title;

    protected ?string $description;

    public function __construct(string $id, string $title, ?string $description = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
    }
}
