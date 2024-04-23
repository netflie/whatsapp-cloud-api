<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\MultiProduct\Action;

final class MultiProductMessage extends Message
{
    /**
     * {@inheritdoc}
     */
    protected string $type = 'product_list';

    private string $header;

    private string $body;

    private int $catalog_id;

    private ?string $footer;

    private Action $action;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $to, int $catalog_id, Action $action, string $header, string $body, ?string $footer, ?string $reply_to)
    {
        $this->header = $header;
        $this->body = $body;
        $this->catalog_id = $catalog_id;
        $this->footer = $footer;
        $this->action = $action;

        parent::__construct($to, $reply_to);
    }

    public function header(): array
    {
        return [
            'type' => 'text',
            'text' => $this->header,
        ];
    }

    public function body(): string
    {
        return $this->body;
    }

    public function catalog_id(): int
    {
        return $this->catalog_id;
    }

    public function footer(): ?string
    {
        return $this->footer;
    }

    public function sections(): array
    {
        return $this->action->sections();
    }
}
