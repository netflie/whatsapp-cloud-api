<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification;

final class TemplateNotification extends Notification
{
    private Template\Status $status;

    public function __construct(
        string $id,
        Support\Business $business,
        string $received_at
    ) {
        parent::__construct($id, $business, $received_at);
    }

    public function withStatus(Template\Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function event(): ?string
    {
        return $this->status?->event();
    }

    public function templateId(): ?string
    {
        return $this->status?->templateId();
    }

    public function templateName(): ?string
    {
        return $this->status?->templateName();
    }

    public function templateLanguage(): ?string
    {
        return $this->status?->templateLanguage();
    }

    public function reason(): ?string
    {
        return $this->status?->reason();
    }
}
