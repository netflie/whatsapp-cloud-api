<?php

namespace Netflie\WhatsAppCloudApi\Request\TemplateRequest;

use Netflie\WhatsAppCloudApi\Enums\TemplateCategoryEnum;
use Netflie\WhatsAppCloudApi\Request;

final class MessageTemplatesRequest extends Request
{
    /**
     * @var string Template fields.
     */
    private string $fields;

    /**
     * @var int Limit of templates to return.
     */
    private int $limit;

    /**
     * @var string Template status.
     */
    private null|string $status;

    /**
     * @var string WhatsApp Business Account Id.
     */
    private string $business_id;

    public function __construct(
        string $fields,
        int $limit = 50,
        null|string|TemplateCategoryEnum $status = null,
        string $access_token,
        string $business_id,
        ?int $timeout = null
    )
    {
        $this->fields = $fields;
        $this->limit = $limit;
        $this->status = is_string($status) && !empty($status) ? $status :
            ($status instanceof TemplateCategoryEnum ? $status->value : null);
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
        return $this->business_id . '/message_templates?'
            . 'fields=' . $this->fields
            . '&limit=' . $this->limit
            . ($this->status !== null ? "&status={$this->status}" : '');
    }
}
