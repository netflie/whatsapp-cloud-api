<?php

namespace Netflie\WhatsAppCloudApi\Tests\Integration;

use Netflie\WhatsAppCloudApi\Message\Document\DocumentId;
use Netflie\WhatsAppCloudApi\Message\Document\DocumentLink;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
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

    public function test_send_document_with_id()
    {
        $media_id = new MediaObjectID('341476474779872');
        $response = $this->whatsapp_app_cloud_api->sendDocument(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $media_id,
            'whatsapp-cloud-api-from-id.pdf',
            'WhastApp API Cloud Guide'
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_document_with_url()
    {
        $link_id = new LinkID('https://netflie.es/wp-content/uploads/2022/05/image.png');
        $response = $this->whatsapp_app_cloud_api->sendDocument(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $link_id,
            'whatsapp-cloud-api-from-link.png',
            'WhastApp API Cloud Guide'
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_template_without_components()
    {
        $response = $this->whatsapp_app_cloud_api->sendTemplate(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'hello_world'
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_template_with_components()
    {
        $component_body = [
            [
                'type' => 'text',
                'text' => '*Mr Jones*',
            ],
        ];
        $component_buttons = [
            [
                'type' => 'button',
                'sub_type' => 'quick_reply',
                'index' => 0,
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => 'Yes',
                    ]
                ]
            ],
            [
                'type' => 'button',
                'sub_type' => 'quick_reply',
                'index' => 1,
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => 'No',
                    ]
                ]
            ]
        ];

        $components = new Component([], $component_body, $component_buttons);
        $response = $this->whatsapp_app_cloud_api->sendTemplate(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'sample_issue_resolution',
            'en_US',
            $components
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_audio_with_url()
    {
        $link_id = new LinkID('https://netflie.es/wp-content/uploads/2022/05/sample3.aac');
        $response = $this->whatsapp_app_cloud_api->sendAudio(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $link_id,
            'whatsapp-cloud-api-from-link.ogg',
            'WhastApp API Cloud Guide'
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_image_with_url()
    {
        $link_id = new LinkID('https://netflie.es/wp-content/uploads/2022/05/whatsapp_cloud_api_banner-1.png');
        $response = $this->whatsapp_app_cloud_api->sendImage(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $link_id,
            'whatsapp-cloud-api-from-link.png',
            'WhastApp Business API Cloud'
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_video()
    {
        $link_id = new LinkID('https://filesamples.com/samples/video/mp4/sample_640x360.mp4');
        $response = $this->whatsapp_app_cloud_api->sendVideo(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $link_id,
            'A video sample.'
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }
}
