<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

final class TextHeader extends Header
{
    protected string $type = 'text';
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getBody(): array
    {
        return [
            'type' => $this->type,
            'text' => $this->text,
        ];
    }
}