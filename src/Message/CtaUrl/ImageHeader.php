<?php

namespace Netflie\WhatsAppCloudApi\Message\CtaUrl;

final class ImageHeader extends Header
{
    protected string $link;

    public function __construct(string $link)
    {
        parent::__construct('image');

        $this->link = $link;
    }

    public function getBody(): array
    {
        return [
            'type' => $this->type,
            'image' => [
                'link' => $this->link,
            ],
        ];
    }
}
