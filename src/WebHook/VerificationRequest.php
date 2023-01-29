<?php

namespace Netflie\WhatsAppCloudApi\WebHook;

final class VerificationRequest
{
    /**
     * Verify Token field configured in your app's App Dashboard.
     * @link https://developers.facebook.com/docs/graph-api/webhooks/getting-started?locale=en_US#configure-webhooks-product
     */
    protected string $verify_token;

    public function __construct(string $verify_token)
    {
        $this->verify_token = $verify_token;
    }

    public function validate(array $payload): string
    {
        $mode = $payload['hub_mode'] ?? null;
        $token = $payload['hub_verify_token'] ?? null;
        $challenge = $payload['hub_challenge'] ?? '';

        if ('subscribe' !== $mode || $token !== $this->verify_token) {
            http_response_code(403);

            return $challenge;
        }

        http_response_code(200);

        return $challenge;
    }
}
