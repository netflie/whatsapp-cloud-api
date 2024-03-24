<?php

namespace Netflie\WhatsAppCloudApi\Message;

final class CtaUrlMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'cta_url';

    private string $displayText;

    private string $url;

    private ?string $header = null;

    private ?string $body = null;

    private ?string $footer = null;

    /**
    * {@inheritdoc}
    */
    public function __construct(
        string $to,
        string $displayText,
        string $url,
        ?string $header,
        ?string $body,
        ?string $footer,
        ?string $reply_to
    ) {
        $this->displayText = $displayText;
        $this->url = $url;
        $this->header = $header;
        $this->body = $body;
        $this->footer = $footer;

        parent::__construct($to, $reply_to);
    }

    public function getDisplayText(): string
    {
        return $this->displayText;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function header(): ?string
    {
        return $this->header;
    }

    public function body(): ?string
    {
        return $this->body;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }

    public function action(): array
    {
        return [
            'name' => $this->type,
            'parameters' => [
                'display_text' => $this->displayText,
                'url' => $this->url,
            ],
        ];
    }
}
