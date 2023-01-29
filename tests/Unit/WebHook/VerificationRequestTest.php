<?php

namespace Netflie\WhatsAppCloudApi\Tests\Unit\WebHook;

use Netflie\WhatsAppCloudApi\WebHook\VerificationRequest;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
final class VerificationRequestTest extends TestCase
{
    public function test_verification_request_succeeded()
    {
        $verification_request = new VerificationRequest('super-secret');

        $response = $verification_request->validate([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'super-secret',
            'hub_challenge' => 'challenge_code',
        ]);

        $this->assertEquals('challenge_code', $response);
        $this->assertEquals(200, http_response_code());
    }

    public function test_verification_request_fails()
    {
        $verification_request = new VerificationRequest('super-secret');

        $response = $verification_request->validate([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'bad-super-secret',
            'hub_challenge' => 'challenge_code',
        ]);

        $this->assertEquals('challenge_code', $response);
        $this->assertEquals(403, http_response_code());
    }
}
