<?php

namespace Netflie\WhatsAppCloudApi\Message\CtaUrl;

final class VideoHeader extends Header
{
    protected string $link;

    public function __construct(string $link)
    {
        parent::__construct('video');

        $this->link = $link;
    }

    public function getBody(): array
    {
        return [
            'type' => $this->type,
            'video' => [
                'link' => $this->link,
            ],
        ];
    }
}
