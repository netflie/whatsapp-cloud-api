<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Error
{
    protected int $code;

    protected string $title;
    
    protected string $details;

    public function __construct(int $code, string $title, ?string $details = null)
    {
        $this->code = $code;
        $this->title = $title;
    }

    public function code(): int
    {
        return $this->code;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function details(): string
    {
        return $this->details;
    }
}
