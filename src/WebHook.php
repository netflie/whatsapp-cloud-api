<?php

namespace Netflie\WhatsAppCloudApi;

use Netflie\WhatsAppCloudApi\WebHook\VerificationRequest;

class WebHook
{
    /**
     * Verify a webhook anytime you configure a new one in your App Dashboard.
     *
     * @param  array  $payload Query string parameters received in your endpoint URL.
     * @return string          Challenge sent by Meta (Facebook)
     */
    public function verify(array $payload, string $verify_token): string
    {
        return (new VerificationRequest($verify_token))
            ->validate($payload);
    }
}
