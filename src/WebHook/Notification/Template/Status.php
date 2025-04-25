<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Template;

final class Status
{
    private string $event;
    private string $message_template_id;
    private string $message_template_name;
    private string $message_template_language;
    private string $reason;

    public function __construct(
        string $event,
        string $message_template_id,
        string $message_template_name,
        string $message_template_language,
        string $reason
    ) {
        $this->event = $event;
        $this->message_template_id = $message_template_id;
        $this->message_template_name = $message_template_name;
        $this->message_template_language = $message_template_language;
        $this->reason = $reason;
    }

    public function event(): string
    {
        return $this->event;
    }

    public function templateId(): string
    {
        return $this->message_template_id;
    }

    public function templateName(): string
    {
        return $this->message_template_name;
    }

    public function templateLanguage(): string
    {
        return $this->message_template_language;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
