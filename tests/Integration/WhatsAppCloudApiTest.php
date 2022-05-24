<?php

namespace Netflie\WhatsAppCloudApi\Tests\Integration;

use Netflie\WhatsAppCloudApi\Tests\WhatsAppCloudApiTestConfiguration;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
final class WhatsAppCloudApiTest extends TestCase
{
    private $whatsapp_app_cloud_api;

    public function setUp(): void
    {
        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => WhatsAppCloudApiTestConfiguration::$from_phone_number_id,
            'access_token' => WhatsAppCloudApiTestConfiguration::$access_token,
        ]);
    }

    public function test_send_text_message()
    {
        $response = $this->whatsapp_app_cloud_api->sendTextMessage(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es',
            true
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }
}