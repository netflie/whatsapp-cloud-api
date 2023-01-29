<?php

namespace Netflie\WhatsAppCloudApi\Tests\Unit;

use GuzzleHttp\Psr7;
use Netflie\WhatsAppCloudApi\Client;
use Netflie\WhatsAppCloudApi\Http\ClientHandler;
use Netflie\WhatsAppCloudApi\Http\RawResponse;
use Netflie\WhatsAppCloudApi\Message\Contact\ContactName;
use Netflie\WhatsAppCloudApi\Message\Contact\Phone;
use Netflie\WhatsAppCloudApi\Message\Contact\PhoneType;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;
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

    private const TEST_GRAPH_VERSION = 'v15.0';

    private $whatsapp_app_cloud_api;
    private $client_handler;
    private $faker;
    private $access_token;
    private $from_phone_number_id;

    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create();

        $this->client_handler = $this->prophesize(ClientHandler::class);
        $this->access_token = $this->faker->uuid;
        $this->from_phone_number_id = $this->faker->uuid;

        $this->whatsapp_app_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $this->from_phone_number_id,
            'access_token' => $this->access_token,
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
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->sendTextMessage(
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
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $media_id = new MediaObjectID($document_id);
        $response = $this->whatsapp_app_cloud_api->sendDocument(
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
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->sendTemplate(
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

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'audio',
            'audio' => [
                'id' => $document_id,
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
        $response = $this->whatsapp_app_cloud_api->sendAudio(
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

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'image',
            'image' => [
                'caption' => $caption,
                'id' => $document_id,
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
        $response = $this->whatsapp_app_cloud_api->sendImage(
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

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'video',
            'video' => [
                'link' => $video_link,
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

        $link_id = new LinkID($video_link);
        $response = $this->whatsapp_app_cloud_api->sendVideo(
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

        $body = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'sticker',
            'sticker' => [
                'link' => $sticker_link,
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
        $response = $this->whatsapp_app_cloud_api->sendSticker(
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
        ];
        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $this->client_handler
            ->postJsonData($url, $body, $headers, Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(new RawResponse($headers, $this->successfulMessageNodeResponse(), 200));

        $response = $this->whatsapp_app_cloud_api->sendLocation(
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

    public function test_send_contact()
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

        $response = $this->whatsapp_app_cloud_api->sendList(
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

    public function test_mark_a_message_as_read()
    {
        $to = $this->faker->phoneNumber;
        $url = $this->buildMessageRequestUri();
        $text_message = $this->faker->text;
        $preview_url = $this->faker->boolean;

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

    private function successfulMessageNodeResponse(): string
    {
        return '{"messaging_product": "whatsapp", "contacts": [{"input": "PHONE_NUMBER", "wa_id": "WHATSAPP_ID"}], "messages": [{"id": "wamid.ID"}]}';
    }

    private function failedMessageResponse(): string
    {
        return '{"error":{"message":"Invalid OAuth access token - Cannot parse access token","type":"OAuthException","code":190,"fbtrace_id":"AbJuG-rMVv36mjw-r78mKwg"}}';
    }
}
