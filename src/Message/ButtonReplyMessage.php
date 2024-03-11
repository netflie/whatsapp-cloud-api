<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;

class ButtonReplyMessage extends Message
{
    protected string $type = 'button';

    private string $body;

    private ?string $footer;

    private ButtonAction $action;

    public function __construct(string $to, string $body, ButtonAction $action, ?string $footer = null, ?string $reply_to = null)
    {
        $this->body = $body;
        $this->action = $action;
        $this->footer = $footer;

        parent::__construct($to, $reply_to);
    }

    public function body(): string
    {
        return $this->body;
    }

    public function action(): ButtonAction
    {
        return $this->action;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }
}
