<?php

namespace Netflie\WhatsAppCloudApi\Message\CtaUrl;

use Netflie\WhatsAppCloudApi\Message\Media\MediaID;

final class DocumentHeader extends Header
{
    protected string $type = 'document';
    private MediaID $id;
    private string $filename;

    public function __construct(MediaID $id, string $filename)
    {
        $this->id = $id;
        $this->filename = $filename;
    }

    public function getBody(): array
    {
        return [
            'type' => $this->type,
            'document' => [
                $this->id->type() => $this->id->value(),
                'filename' => $this->filename,
            ],
        ];
    }
}
