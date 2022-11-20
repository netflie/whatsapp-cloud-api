<?php

namespace Netflie\WhatsAppCloudApi\Tests\Unit\WebHook;

use Netflie\WhatsAppCloudApi\WebHook;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
final class WebHookTest extends TestCase
{
    private WebHook $webhook;

    public function setUp(): void
    {
        parent::setUp();

        $this->webhook = new WebHook();
    }

    public function test_verify_a_webhook()
    {
        $response = $this->webhook->verify([
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'verify-token',
            'hub_challenge' => 'challenge_code',
        ], 'verify-token');

        $this->assertEquals('challenge_code', $response);
        $this->assertEquals(200, http_response_code());
    }
}
