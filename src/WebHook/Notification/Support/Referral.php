<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Referral
{
    private string $source_id;

    private string $source_url;

    private string $source_type;

    private string $headline;

    private string $body;

    private string $media_type;

    private string $media_url;

    private string $thumbnail_url;

    public function __construct(
        string $source_id,
        string $source_url,
        string $source_type,
        string $headline,
        string $body,
        string $media_type,
        string $media_url,
        string $thumbnail_url
    ) {
        $this->source_id = $source_id;
        $this->source_url = $source_url;
        $this->source_type = $source_type;
        $this->headline = $headline;
        $this->body = $body;
        $this->media_type = $media_type;
        $this->media_url = $media_url;
        $this->thumbnail_url = $thumbnail_url;
    }

    public function sourceId(): string
    {
        return $this->source_id;
    }

    public function sourceUrl(): string
    {
        return $this->source_url;
    }

    public function sourceType(): string
    {
        return $this->source_type;
    }

    public function headline(): string
    {
        return $this->headline;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function mediaType(): string
    {
        return $this->media_type;
    }

    public function mediaUrl(): string
    {
        return $this->media_url;
    }

    public function thumbnailUrl(): string
    {
        return $this->thumbnail_url;
    }
}
