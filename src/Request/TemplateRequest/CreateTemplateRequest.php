<?php

namespace Netflie\WhatsAppCloudApi\Request\TemplateRequest;

use Netflie\WhatsAppCloudApi\Request;

/**
 * Request object for creating a WhatsApp message template.
 *
 * This request sends a POST request to the Graph API to create a new template
 * under the specified business ID with the provided payload data.
 */
final class CreateTemplateRequest extends Request
{
    private array $payload;
    private string $business_id;

    /**
     * Constructor to initialize the template creation request.
     *
     * @param string   $access_token Access token for API authentication.
     * @param string   $business_id  The WhatsApp Business Account ID.
     * @param array    $payload      The template definition payload.
     * @param int|null $timeout      Optional request timeout in seconds.
     */
    public function __construct(
        string $access_token,
        string $business_id,
        array $payload,
        ?int $timeout = null
    ) {
        $this->payload = $payload;
        $this->business_id = $business_id;

        parent::__construct($access_token, $timeout);
    }

    /**
     * Returns the payload to be sent as the request body.
     *
     * @return array The request body.
     */
    public function body(): array
    {
        return $this->payload;
    }

    /**
     * Returns the Graph API node path for creating the template.
     *
     * @return string The URI path segment (e.g., {business_id}/message_templates).
     */
    public function nodePath(): string
    {
        return $this->business_id . '/message_templates';
    }
}
