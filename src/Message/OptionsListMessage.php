<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;

class OptionsListMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'list';

	protected string $header;

	protected string $body;

	protected string $footer;

    protected Action $action;

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
        return ['button' => $this->action->button(), 'sections' => $this->action->sections()];
    }
}
