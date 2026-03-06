<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

use Netflie\WhatsAppCloudApi\Message\Media\MediaID;

final class ImageHeader extends Header
{
    protected string $type = 'image';
    private MediaID $id;

    public function __construct(MediaID $id)
    {
        $this->id = $id;
    }

    public function getBody(): array
    {
        return [
            'type' => $this->type,
            'image' => [
                $this->id->type() => $this->id->value(),
            ],
        ];
    }
}
