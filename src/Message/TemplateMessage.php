<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\Template\Component;

final class TemplateMessage extends Message
{
    /**
    * {@inheritdoc}
    */
    protected string $type = 'template';

    /**
     * Name of the template
     * @link https://business.facebook.com/wa/manage/message-templates/ Dashboard to manage (create, edit and delete) templates.
     */
    private string $name;

    /**
     * @link https://developers.facebook.com/docs/whatsapp/api/messages/message-templates#supported-languages See supported language codes.
     */
    private string $language;

    /**
     * Templates header, body and buttons can be personalized
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-message-templates See how you can personalized your templates.
     */
    private ?Component $components;

    /**
    * {@inheritdoc}
    */
    public function __construct(string $to, string $name, string $language = 'en_US', ?Component $components = null, ?string $reply_to = null)
    {
        $this->name = $name;
        $this->language = $language;
        $this->components = $components;

        parent::__construct($to, $reply_to);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function language(): string
    {
        return $this->language;
    }

    public function header(): array
    {
        return $this->components
            ? $this->components->header()
            : [];
    }

    public function body(): array
    {
        return $this->components
            ? $this->components->body()
            : [];
    }

    public function buttons(): array
    {
        return $this->components
            ? $this->components->buttons()
            : [];
    }
}
