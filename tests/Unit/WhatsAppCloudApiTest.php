<?php

namespace Netflie\WhatsAppCloudApi\Tests\Unit;

use Netflie\WhatsAppCloudApi\Client;
use Netflie\WhatsAppCloudApi\Http\ClientHandler;
use Netflie\WhatsAppCloudApi\Http\RawResponse;
use Netflie\WhatsAppCloudApi\Message\Document\DocumentId;
use Netflie\WhatsAppCloudApi\Message\Document\DocumentLink;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Tests\WhatsAppCloudApiTestConfiguration;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @group unit
 */
final class WhatsAppCloudApiTest extends TestCase
{
    use ProphecyTrait;

    private $whatsapp_app_cloud_api;
    private $client_handler;
    private $faker;

    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create();
        $this->client_handler = $this->prophesize(ClientHandler::class);

        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => WhatsAppCloudApiTestConfiguration::$from_phone_number_id,
            'access_token' => WhatsAppCloudApiTestConfiguration::$access_token,
            'client_handler' => $this->client_handler->reveal(),
        ]);
    }

    public function test_send_text_message()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildRequestUri();
        $text_message = $this->faker->text;
        $preview_url = $this->faker->boolean;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => $preview_url,
                'body' => $text_message,
            ],
        ];
        $encoded_body = json_encode($body);
        $headers = [
            'Authorization' => 'Bearer ' . WhatsAppCloudApiTestConfiguration::$access_token,
            'Content-Type' => 'application/json',
        ];

        $this->client_handler
            ->send($url, $encoded_body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $encoded_body, 200));

        $response = $this->whatsapp_app_cloud_api->sendTextMessage(
            $to,
            $text_message,
            $preview_url
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($body, $response->decodedBody());
        $this->assertEquals($encoded_body, $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_document_id()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildRequestUri();
        $caption = $this->faker->text;
        $filename = $this->faker->text;
        $document_id = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'document',
            'document' => [
                'caption' => $caption,
                'filename' => $filename,
                'id' => $document_id,
            ],
        ];
        $encoded_body = json_encode($body);
        $headers = [
            'Authorization' => 'Bearer ' . WhatsAppCloudApiTestConfiguration::$access_token,
            'Content-Type' => 'application/json',
        ];

        $this->client_handler
            ->send($url, $encoded_body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $encoded_body, 200));

        $media_id = new MediaObjectID($document_id);
        $response = $this->whatsapp_app_cloud_api->sendDocument(
            $to,
            $media_id,
            $filename, $caption
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($body, $response->decodedBody());
        $this->assertEquals($encoded_body, $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_document_link()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildRequestUri();
        $caption = $this->faker->text;
        $filename = $this->faker->text;
        $document_link = $this->faker->url;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'document',
            'document' => [
                'caption' => $caption,
                'filename' => $filename,
                'link' => $document_link,
            ],
        ];
        $encoded_body = json_encode($body);
        $headers = [
            'Authorization' => 'Bearer ' . WhatsAppCloudApiTestConfiguration::$access_token,
            'Content-Type' => 'application/json',
        ];

        $this->client_handler
            ->send($url, $encoded_body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $encoded_body, 200));

        $link_id = new LinkID($document_link);
        $response = $this->whatsapp_app_cloud_api->sendDocument(
            $to,
            $link_id,
            $filename, $caption
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($body, $response->decodedBody());
        $this->assertEquals($encoded_body, $response->body());
        $this->assertEquals(false, $response->isError());
    }

    private function buildRequestUri(): string
    {
        return Client::BASE_GRAPH_URL . '/' . WhatsAppCloudApiTestConfiguration::$graph_version . '/' . WhatsAppCloudApiTestConfiguration::$from_phone_number_id . '/messages';
    }
}
