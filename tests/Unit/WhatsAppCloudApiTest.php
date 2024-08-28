<?php

namespace Netflie\WhatsAppCloudApi\Tests\Unit;

use GuzzleHttp\Psr7;
use Netflie\WhatsAppCloudApi\Client;
use Netflie\WhatsAppCloudApi\Http\ClientHandler;
use Netflie\WhatsAppCloudApi\Http\RawResponse;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Message\Contact\ContactName;
use Netflie\WhatsAppCloudApi\Message\Contact\Phone;
use Netflie\WhatsAppCloudApi\Message\Contact\PhoneType;
use Netflie\WhatsAppCloudApi\Message\CtaUrl\TitleHeader;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
use Netflie\WhatsAppCloudApi\Message\MultiProduct\Action as MultiProductAction;
use Netflie\WhatsAppCloudApi\Message\MultiProduct\Row as MultiProductRow;
use Netflie\WhatsAppCloudApi\Message\MultiProduct\Section as MultiProductSection;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Response\ResponseException;
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

    private const TEST_GRAPH_VERSION = 'v18.0';

    private $whatsapp_app_cloud_api;
    private $client_handler;
    private $faker;
    private $access_token;
    private $from_phone_number_id;
    private $business_id;

    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create();

        $this->client_handler = $this->prophesize(ClientHandler::class);
        $this->access_token = $this->faker->uuid;
        $this->from_phone_number_id = $this->faker->uuid;
        $this->business_id = $this->faker->uuid;

        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $this->from_phone_number_id,
            'access_token' => $this->access_token,
            'business_id' => $this->business_id,
            'client_handler' => $this->client_handler->reveal(),
        ]);
    }

    public function test_send_text_message_fails()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
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
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse([], $this->failedMessageResponse(), 401));

        $this->expectException(ResponseException::class);
        $response = $this->whatsapp_app_cloud_api->sendTextMessage(
            $to,
            $text_message,
            $preview_url
        );
    }

    public function test_send_text_message()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $text_message = $this->faker->text;
        $preview_url = $this->faker->boolean;
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => $preview_url,
                'body' => $text_message,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendTextMessage(
                $to,
                $text_message,
                $preview_url
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_document_id()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $caption = $this->faker->text;
        $filename = $this->faker->text;
        $document_id = $this->faker->uuid;
        $reply_to = $this->faker->uuid;

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
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $media_id = new MediaObjectID($document_id);
        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendDocument(
                $to,
                $media_id,
                $filename,
                $caption
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_document_link()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
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
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $link_id = new LinkID($document_link);
        $response = $this->whatsapp_app_cloud_api->sendDocument(
            $to,
            $link_id,
            $filename,
            $caption
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_template_without_components()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $template_name = $this->faker->name;
        $language = $this->faker->locale;
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $template_name,
                'language' => ['code' => $language],
                'components' => [],
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendTemplate(
                $to,
                $template_name,
                $language
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_template_with_components()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $template_name = $this->faker->name;
        $language = $this->faker->locale;

        $component_header = [
            [
                'type' => 'text',
                'text' => 'I\'m a heder',
            ],
        ];
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
                    ],
                ],
            ],
            [
                'type' => 'button',
                'sub_type' => 'quick_reply',
                'index' => 1,
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => 'No',
                    ],
                ],
            ],
        ];

        $components = new Component($component_header, $component_body, $component_buttons);

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $template_name,
                'language' => ['code' => $language],
                'components' => [
                    [
                        'type' => 'header',
                        'parameters' => $component_header,
                    ],
                    [
                        'type' => 'body',
                        'parameters' => $component_body,
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'quick_reply',
                        'index' => 0,
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => 'Yes',
                            ],
                        ],
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'quick_reply',
                        'index' => 1,
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => 'No',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $components = new Component($component_header, $component_body, $component_buttons);
        $response = $this->whatsapp_app_cloud_api->sendTemplate(
            $to,
            $template_name,
            $language,
            $components
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_audio_id()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $document_id = $this->faker->uuid;
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'audio',
            'audio' => [
                'id' => $document_id,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $media_id = new MediaObjectID($document_id);
        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendAudio(
                $to,
                $media_id
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_audio_link()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $document_link = $this->faker->url;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'audio',
            'audio' => [
                'link' => $document_link,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $link_id = new LinkID($document_link);
        $response = $this->whatsapp_app_cloud_api->sendAudio(
            $to,
            $link_id
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_image_id()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $caption = $this->faker->text;
        $document_id = $this->faker->uuid;
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'image',
            'image' => [
                'caption' => $caption,
                'id' => $document_id,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $media_id = new MediaObjectID($document_id);
        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendImage(
                $to,
                $media_id,
                $caption
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_image_link()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $caption = $this->faker->text;
        $document_link = $this->faker->url;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'image',
            'image' => [
                'caption' => $caption,
                'link' => $document_link,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $link_id = new LinkID($document_link);
        $response = $this->whatsapp_app_cloud_api->sendImage(
            $to,
            $link_id,
            $caption
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_video_with_link()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $video_link = $this->faker->url;
        $caption = $this->faker->text;
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'video',
            'video' => [
                'link' => $video_link,
                'caption' => $caption,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $link_id = new LinkID($video_link);
        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendVideo(
                $to,
                $link_id,
                $caption
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_video_with_id()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $video_id = $this->faker->uuid;
        $caption = $this->faker->text;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'video',
            'video' => [
                'id' => $video_id,
                'caption' => $caption,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $media_id = new MediaObjectID($video_id);
        $response = $this->whatsapp_app_cloud_api->sendVideo(
            $to,
            $media_id,
            $caption
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_sticker()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $sticker_link = $this->faker->url;
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'sticker',
            'sticker' => [
                'link' => $sticker_link,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $media_id = new LinkID($sticker_link);
        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendSticker(
                $to,
                $media_id
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_location()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $latitude = $this->faker->latitude;
        $longitude = $this->faker->latitude;
        $name = $this->faker->city;
        $address = $this->faker->address;
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'location',
            'location' => [
                'longitude' => $longitude,
                'latitude' => $latitude,
                'name' => $name,
                'address' => $address,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendLocation(
                $to,
                $longitude,
                $latitude,
                $name,
                $address
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_location_request()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $message = $this->faker->text(1024);

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'location_request_message',
                'body' => ['text' => $message],
                'action' => [
                    'name' => 'send_location',
                ],
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->sendLocationRequest(
            $to,
            $message
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_contact()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName;
        $phone = $this->faker->e164PhoneNumber;
        $phone_type = PhoneType::CELL();
        $reply_to = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'contacts',
            'contacts' => [
                [
                    'name' => [
                        'formatted_name' => "$first_name $last_name",
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                    ],
                    'phones' => [
                        [
                            'phone' => $phone,
                            'type' => $phone_type,
                        ],
                    ],
                ],
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $contact_name = new ContactName($first_name, $last_name);
        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendContact(
                $to,
                $contact_name,
                new Phone($phone, $phone_type)
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_contact_with_wa_id()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $first_name = $this->faker->firstName();
        $last_name = $this->faker->lastName;
        $phone = $this->faker->e164PhoneNumber;
        $phone_type = PhoneType::CELL();

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'contacts',
            'contacts' => [
                [
                    'name' => [
                        'formatted_name' => "$first_name $last_name",
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                    ],
                    'phones' => [
                        [
                            'phone' => $phone,
                            'type' => $phone_type,
                            'wa_id' => $phone,
                        ],
                    ],
                ],
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $contact_name = new ContactName($first_name, $last_name);
        $response = $this->whatsapp_app_cloud_api->sendContact(
            $to,
            $contact_name,
            new Phone($phone, $phone_type, $phone)
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_list()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $reply_to = $this->faker->uuid;

        $listHeader = ['type' => 'text', 'text' => $this->faker->text(60)];
        $listBody = ['text' => $this->faker->text(1024)];
        $listFooter = ['text' => $this->faker->text(60)];

        $listRows = [
            ['id' => $this->faker->uuid, 'title' => $this->faker->text(24), 'description' => $this->faker->text(72)],
            ['id' => $this->faker->uuid, 'title' => $this->faker->text(24), 'description' => $this->faker->text(72)],
        ];
        $listSections = [['title' => $this->faker->text, 'rows' => $listRows]];
        $listAction = ['button' => $this->faker->text, 'sections' => $listSections];

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'header' => $listHeader,
                'body' => $listBody,
                'footer' => $listFooter,
                'action' => $listAction,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $actionSections = [];

        foreach ($listAction['sections'] as $section) {
            $sectionRows = [];

            foreach ($section['rows'] as $row) {
                $sectionRows[] = new Row($row['id'], $row['title'], $row['description']);
            }

            $actionSections[] = new Section($section['title'], $sectionRows);
        }

        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendList(
                $to,
                $listHeader['text'],
                $listBody['text'],
                $listFooter['text'],
                new Action($listAction['button'], $actionSections),
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_cta_url()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $reply_to = $this->faker->uuid;

        $ctaHeader = ['type' => 'text', 'text' => $this->faker->text(60)];
        $ctaBody = ['text' => $this->faker->text(1024)];
        $ctaFooter = ['text' => $this->faker->text(60)];
        $ctaAction = [
            'name' => 'cta_url',
            'parameters' => [
                'display_text' => $this->faker->text(24),
                'url' => $this->faker->url,
            ],
        ];

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'cta_url',
                'header' => $ctaHeader,
                'body' => $ctaBody,
                'footer' => $ctaFooter,
                'action' => $ctaAction,
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $header = new TitleHeader($ctaHeader['text']);

        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendCtaUrl(
                $to,
                $ctaAction['parameters']['display_text'],
                $ctaAction['parameters']['url'],
                $header,
                $ctaBody['text'],
                $ctaFooter['text'],
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_reply_buttons()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $reply_to = $this->faker->uuid;

        $buttonRows = [
            ['id' => $this->faker->uuid, 'title' => $this->faker->text(10)],
            ['id' => $this->faker->uuid, 'title' => $this->faker->text(10)],
            ['id' => $this->faker->uuid, 'title' => $this->faker->text(10)],
        ];
        $buttonAction = ['buttons' => []];

        foreach ($buttonRows as $button) {
            $buttonAction['buttons'][] = [
                'type' => 'reply',
                'reply' => $button,
            ];
        }

        $message = $this->faker->text(50);
        $header = $this->faker->text(50);
        $footer = $this->faker->text(50);

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => ['text' => $message],
                'action' => $buttonAction,
                'header' => ['type' => 'text', 'text' => $header],
                'footer' => ['text' => $footer],
            ],
            'context' => [
                'message_id' => $reply_to,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $actionButtons = [];

        foreach ($buttonRows as $button) {
            $actionButtons[] = new Button($button['id'], $button['title']);
        }

        $response = $this->whatsapp_app_cloud_api
            ->replyTo($reply_to)
            ->sendButton(
                $to,
                $message,
                new ButtonAction($actionButtons),
                $header,
                $footer
            );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_upload_media()
    {
        $url = $this->buildMediaRequestUri();
        $form = [
            [
                'name' => 'file',
                'contents' => Psr7\Utils::tryFopen('tests/Support/netflie.png', 'r'),
            ],
            [
                'name' => 'type',
                'contents' => 'image/png',
            ],
            [
                'name' => 'messaging_product',
                'contents' => 'whatsapp',
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];
        $response_body = '{"id":"<MEDIA_ID>"}';

        $this->client_handler
            ->postFormData($url, Argument::that(function ($arg) use ($form) {
                return json_encode($arg) == json_encode($form);
            }), $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $response_body, 200));

        $response = $this->whatsapp_app_cloud_api->uploadMedia('tests/Support/netflie.png');

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($response_body, true), $response->decodedBody());
        $this->assertEquals($response_body, $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_download_media()
    {
        $media_id = (string) $this->faker->randomNumber;
        $url = $this->buildBaseUri() . $media_id;
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];
        $media_url_response_body = '{"url": "<MEDIA_URL>"}';
        $binary_media_response_body = $this->faker->text;

        $this->client_handler
            ->get($url, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $media_url_response_body, 200));
        $this->client_handler
            ->get('<MEDIA_URL>', $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $binary_media_response_body, 200));

        $response = $this->whatsapp_app_cloud_api->downloadMedia($media_id);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals([], $response->decodedBody());
        $this->assertEquals($binary_media_response_body, $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_business_profile()
    {
        $fields = 'about';
        $url = $this->buildBusinessProfileRequestUri() . '?fields=' . $fields;
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];
        $response_body = '{"data":[{"about":"<ABOUT>","messaging_product":"whatsapp"}]}';

        $this->client_handler
            ->get($url, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $response_body, 200));

        $response = $this->whatsapp_app_cloud_api->businessProfile($fields);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($response_body, $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_update_business_profile()
    {
        $url = $this->buildBusinessProfileRequestUri();
        $body = [
            'about' => 'About text',
            'email' => 'my-email@email.com',
            'messaging_product' => 'whatsapp',
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];
        $response_body = '{"success":true}';

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $response_body, 200));

        $response = $this->whatsapp_app_cloud_api->updateBusinessProfile([
            'about' => 'About text',
            'email' => 'my-email@email.com',
        ]);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($response_body, $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_catalog_message()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $message = $this->faker->text(1024);
        $footer = $this->faker->text(60);
        $product_retailer_id = $this->faker->text;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'catalog_message',
                'body' => ['text' => $message],
                'footer' => ['text' => $footer],
                'action' => [
                    'name' => 'catalog_message',
                    'parameters' => ['thumbnail_product_retailer_id' => $product_retailer_id],
                ],
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->sendCatalog(
            $to,
            $message,
            $footer,
            $product_retailer_id
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_multi_product()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $header = $this->faker->text(20);
        $message = $this->faker->text(1024);
        $footer = $this->faker->text(60);
        $catalog_id = $this->faker->randomNumber();

        $rows1 = [
            ['product_retailer_id' => $this->faker->uuid],
            ['product_retailer_id' => $this->faker->uuid],
            ['product_retailer_id' => $this->faker->uuid],
        ];

        $rows2 = [
            ['product_retailer_id' => $this->faker->uuid],
            ['product_retailer_id' => $this->faker->uuid],
        ];


        $sections = [
            ['title' => $this->faker->text, 'product_items' => $rows1],
            ['title' => $this->faker->text, 'product_items' => $rows2],
        ];
        $actions = ['catalog_id' => $catalog_id, 'sections' => $sections];

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'product_list',
                'header' => ['type' => 'text', 'text' => $header],
                'body' => ['text' => $message],
                'footer' => ['text' => $footer],
                'action' => $actions,
            ],
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $actionSections = [];

        foreach ($actions['sections'] as $section) {
            $sectionRows = [];

            foreach ($section['product_items'] as $row) {
                $sectionRows[] = new MultiProductRow($row['product_retailer_id']);
            }

            $actionSections[] = new MultiProductSection($section['title'], $sectionRows);
        }

        $response = $this->whatsapp_app_cloud_api->sendMultiProduct(
            $to,
            $catalog_id,
            new MultiProductAction($actionSections),
            $header,
            $message,
            $footer
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_mark_a_message_as_read()
    {
        $url = $this->buildMessageRequestUri();
        $body = [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => '<message-id>',
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->markMessageAsRead('<message-id>');

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_reaction_message()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $emoji = $this->faker->emoji;
        $message_id = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'reaction',
            'reaction' => [
                'message_id' => $message_id,
                'emoji' => $emoji,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->sendReaction(
            $to,
            $message_id,
            $emoji
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_remove_reaction_message()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $emoji = '';
        $message_id = $this->faker->uuid;

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'reaction',
            'reaction' => [
                'message_id' => $message_id,
                'emoji' => $emoji,
            ],
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->sendReaction(
            $to,
            $message_id
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(json_decode($this->successfulMessageNodeResponse(), true), $response->decodedBody());
        $this->assertEquals($this->successfulMessageNodeResponse(), $response->body());
        $this->assertEquals(false, $response->isError());
    }

    private function buildBaseUri(): string
    {
        return Client::BASE_GRAPH_URL . '/' . static::TEST_GRAPH_VERSION . '/';
    }

    private function buildMessageRequestUri(): string
    {
        return $this->buildBaseUri() . $this->from_phone_number_id . '/messages';
    }

    private function buildMediaRequestUri(): string
    {
        return $this->buildBaseUri() . $this->from_phone_number_id . '/media';
    }

    private function buildBusinessProfileRequestUri(): string
    {
        return $this->buildBaseUri() . $this->from_phone_number_id . '/whatsapp_business_profile';
    }

    private function successfulMessageNodeResponse(): string
    {
        return '{"messaging_product": "whatsapp", "contacts": [{"input": "PHONE_NUMBER", "wa_id": "WHATSAPP_ID"}], "messages": [{"id": "wamid.ID"}]}';
    }

    private function failedMessageResponse(): string
    {
        return '{"error":{"message":"Invalid OAuth access token - Cannot parse access token","type":"OAuthException","code":190,"fbtrace_id":"AbJuG-rMVv36mjw-r78mKwg"}}';
    }
}
