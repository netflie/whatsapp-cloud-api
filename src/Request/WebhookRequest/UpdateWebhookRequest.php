<?php

namespace Netflie\WhatsAppCloudApi\Request\WebhookRequest;

use Netflie\WhatsAppCloudApi\Request;
use Netflie\WhatsAppCloudApi\Request\RequestWithBody;

final class UpdateWebhookRequest extends Request implements RequestWithBody
{
    /**
     * @var string WhatsApp webhook uri.
     */
    private string $uri;

    /**
     * @var string WhatsApp webhook verify token, if provided.
     */
    private string $verify_token;

    /**
     * @var string WhatsApp Number Id from messages will sent.
     */
    private string $from_phone_number_id;

    public function __construct(string $uri, string $verify_token, string $access_token, string $from_phone_number_id, ?int $timeout = null)
    {
        $this->uri = $uri;
        $this->verify_token = $verify_token;
        $this->from_phone_number_id = $from_phone_number_id;

        parent::__construct($access_token, $timeout);
    }

    /**
     * Returns the raw form of the request.
     *
     * @return array
     */
    public function body(): array
    {
        return [
            'webhook_configuration' => [
                'override_callback_uri' => $this->uri,
                'verify_token' => $this->verify_token,
            ],
        ];
    }

    /**
     * WhatsApp node path.
     *
     * @return string
     */
    public function nodePath(): string
    {
        return $this->from_phone_number_id;
    }
}
