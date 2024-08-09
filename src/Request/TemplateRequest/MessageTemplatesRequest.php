<?php

namespace Netflie\WhatsAppCloudApi\Request\TemplateRequest;

use Netflie\WhatsAppCloudApi\Request;

final class MessageTemplatesRequest extends Request
{
    /**
     * @var string Template fields.
     */
    private string $fields;

    /**
     * @var string WhatsApp Business Account Id.
     */
    private string $business_id;

    public function __construct(string $fields, string $access_token, string $business_id, ?int $timeout = null)
    {
        $this->fields = $fields;
        $this->business_id = $business_id;

        parent::__construct($access_token, $timeout);
    }

    /**
     * Return WhatsApp Number Id for this request.
     *
     * @return string
     */
    public function businessId(): string
    {
        return $this->business_id;
    }

    /**
     * WhatsApp node path.
     *
     * @return string
     */
    public function nodePath(): string
    {
        return $this->business_id . '/message_templates?fields=' . $this->fields;
    }
}
