<?php

namespace Netflie\WhatsAppCloudApi\Template;

use Netflie\WhatsAppCloudApi\Enums\TemplateCategoryEnum;

final class Template
{
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
    private ?array $components;

    /**
     * Template category
     * @link https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates#template-categories See supported template categories.
     */
    private ?TemplateCategoryEnum $category;

    /**
     * Allow category change
     * @link https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates#creating-templates See how to create templates.
     */
    private bool $allow_category_change;

    /**
    * {@inheritdoc}
    */
    public function __construct(
        string $name,
        string $language = 'en_US',
        ?TemplateCategoryEnum $category = null,
        $allow_category_change = true
    ) {
        $this->name = $name;
        $this->language = $language;
        $this->category = $category;
        $this->allow_category_change = $allow_category_change;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function language(): string
    {
        return $this->language;
    }

    public function textHeader(string $text, ?array $exampleStrings = null): self
    {
        $this->components['header'] = [
            'type' => 'HEADER',
            'format' => 'TEXT',
            'text' => $text
        ];

        if ($example) {
            $this->components['header']['example']['header_text'] = $exampleStrings;
        }

        return $this;
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

    public function category(): ?TemplateCategoryEnum
    {
        return $this->category;
    }

    public function allowCategoryChange(): bool
    {
        return $this->allow_category_change;
    }
}
