<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\Button\Action;

final class ButtonMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'button';

    private string $header;

    private string $body;

    private string $footer;

    private Action $action;

    /**
    * {@inheritdoc}
    */
    public function __construct(string $to, string $header, string $body, string $footer, Action $action)
    {
        $this->header = $header;
        $this->body = $body;
        $this->footer = $footer;
        $this->action = $action;

        parent::__construct($to);
    }

    public function header(): string
    {
        return $this->header;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function footer(): string
    {
        return $this->footer;
    }

    public function action(): array
    {
        return ['buttons' => $this->action->buttons()];
    }
}
