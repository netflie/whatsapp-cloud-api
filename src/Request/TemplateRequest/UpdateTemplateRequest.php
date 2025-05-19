<?php

namespace Netflie\WhatsAppCloudApi\Request\TemplateRequest;

use Netflie\WhatsAppCloudApi\Request;

/**
 * Request object for updating an existing WhatsApp message template.
 *
 * This request sends a POST request to the Graph API to update the specified
 * template using the provided template ID and payload.
 */
final class UpdateTemplateRequest extends Request
{
    private string $template_id;
    private array $payload;

    /**
     * Constructor to initialize the template update request.
     *
     * @param string   $access_token Access token for API authentication.
     * @param string   $template_id  The ID of the template to be updated.
     * @param array    $payload      The updated template definition payload.
     * @param int|null $timeout      Optional request timeout in seconds.
     */
    public function __construct(
        string $access_token,
        string $template_id,
        array $payload,
        ?int $timeout = null
    ) {
        $this->template_id = $template_id;
        $this->payload = $payload;

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
     * Returns the Graph API node path for updating the template.
     *
     * @return string The URI path segment (e.g., template ID).
     */
    public function nodePath(): string
    {
        return $this->template_id;
    }
}
