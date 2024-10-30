<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

use Netflie\WhatsAppCloudApi\Message\Media\MediaID;

final class VideoHeader extends Header
{
    protected string $type = 'video';
    private MediaID $id;

    public function __construct(MediaID $id)
    {
        $this->id = $id;
    }

    public function getBody(): array
    {
        return [
            'type' => $this->type,
            'video' => [
                $this->id->type() => $this->id->value(),
            ],
        ];
    }
}