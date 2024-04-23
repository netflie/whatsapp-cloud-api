<?php

namespace Netflie\WhatsAppCloudApi\Tests\Integration;

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
            'business_id' => WhatsAppCloudApiTestConfiguration::$business_id,
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
        $this->markTestIncomplete(
            'This test should send a real media ID.'
        );

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
        $this->markTestIncomplete(
            'Meta deleted the sample_issue_resolution template.'
        );

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

    public function test_send_sticker()
    {
        $link_id = new LinkID('https://raw.githubusercontent.com/WhatsApp/stickers/main/Android/app/src/main/assets/1/01_Cuppy_smile.webp');
        $response = $this->whatsapp_app_cloud_api->sendSticker(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $link_id
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_location()
    {
        $longitude = 39.56939;
        $latitude = 2.65024;
        $name = 'The Paradise';
        $address = 'Mallorca Rd., 07000, Illes Balears (Spain)';
        $response = $this->whatsapp_app_cloud_api->sendLocation(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $longitude,
            $latitude,
            $name,
            $address
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_location_request()
    {
        $body = 'Let\'s start with your pickup. You can either manually *enter an address* or *share your current location*.';
        $response = $this->whatsapp_app_cloud_api->sendLocationRequest(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $body
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_contact()
    {
        $contact_name = new ContactName('Adams', 'Smith');
        $phone = new Phone('34676204577', PhoneType::CELL());
        $response = $this->whatsapp_app_cloud_api->sendContact(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $contact_name,
            $phone
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_contact_with_waid()
    {
        $contact_name = new ContactName('Adams', 'Smith');
        $phone = new Phone(WhatsAppCloudApiTestConfiguration::$contact_phone_number, PhoneType::CELL(), WhatsAppCloudApiTestConfiguration::$contact_waid);
        $response = $this->whatsapp_app_cloud_api->sendContact(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $contact_name,
            $phone
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_list()
    {
        $rows = [
            new Row('1', '⭐️', "Experience wasn't good enough"),
            new Row('2', '⭐⭐️', "Experience could be better"),
            new Row('3', '⭐⭐⭐️', "Experience was ok"),
            new Row('4', '⭐⭐️⭐⭐', "Experience was good"),
            new Row('5', '⭐⭐️⭐⭐⭐️', "Experience was excellent"),
        ];
        $sections = [new Section('Stars', $rows)];
        $action = new Action('Submit', $sections);

        $response = $this->whatsapp_app_cloud_api->sendList(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'Rate your experience',
            'Please consider rating your shopping experience in our website',
            'Thanks for your time',
            $action
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_cta_url()
    {
        $header = new TitleHeader('The header');

        $response = $this->whatsapp_app_cloud_api->sendCtaUrl(
            '<destination-phone-number>',
            'Button text',
            'https://www.example.com',
            $header,
            'The body',
            'The footer',
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_catalog_message()
    {
        $body = 'Hello! Thanks for your interest. Ordering is easy. Just visit our catalog and add items you\'d like to purchase.';
        $footer = 'Best grocery deals on WhatsApp!';
        $response = $this->whatsapp_app_cloud_api->sendCatalog(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $body,
            $footer,
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_multi_product()
    {
        $this->markTestIncomplete(
            'This test should send a real catalog ID and product SKU ID.'
        );

        $rows_section_1 = [
            new MultiProductRow('<product-sku-id>'),
            // can add more
        ];

        $rows_section_2 = [
            new MultiProductRow('<product-sku-id>'),
            // can add more
        ];

        $sections = [
            new MultiProductSection('Section 1', $rows_section_1),
            new MultiProductSection('Section 2', $rows_section_2),
        ];

        $action = new MultiProductAction($sections);
        $catalog_id = '<catalog-id>';
        $header = 'Grocery Collections';
        $body = 'Hello! Thanks for your interest. Here\'s what we can offer you under our grocery collection. Thank you for shopping with us.';
        $footer = 'Subject to T&C';

        $response = $this->whatsapp_app_cloud_api->sendMultiProduct(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $catalog_id,
            $action,
            $header,
            $body,
            $footer // optional
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_reply_buttons()
    {
        $buttonRows = [
            new Button('button-1', 'Yes'),
            new Button('button-2', 'No'),
            new Button('button-3', 'Not Now'),
        ];
        $buttonAction = new ButtonAction($buttonRows);
        $header = 'RATE US';
        $footer = 'Please choose an option';

        $response = $this->whatsapp_app_cloud_api->sendButton(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'Would you like to rate us?',
            $buttonAction,
            $header,
            $footer
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_reaction_message()
    {
        $textMessage = $this->whatsapp_app_cloud_api->sendTextMessage(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'This text will receive a reaction',
            true
        );

        $messageId = $textMessage->decodedBody()['messages'][0]['id'];

        $response = $this->whatsapp_app_cloud_api->sendReaction(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $messageId,
            '👍'
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_send_remove_reaction_message()
    {
        $textMessage = $this->whatsapp_app_cloud_api->sendTextMessage(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            'This text will receive a reaction and then the reaction will be removed',
            true
        );

        $messageId = $textMessage->decodedBody()['messages'][0]['id'];

        $reactToMessage = $this->whatsapp_app_cloud_api->sendReaction(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $messageId,
            '👍'
        );

        // sleep(3); // can delay next request to see reaction

        $response = $this->whatsapp_app_cloud_api->sendReaction(
            WhatsAppCloudApiTestConfiguration::$to_phone_number_id,
            $messageId
        );

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_upload_media()
    {
        $response = $this->whatsapp_app_cloud_api->uploadMedia('tests/Support/netflie.png');

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());

        return $response->decodedBody()['id'];
    }

    /**
     * @depends test_upload_media
     */
    public function test_download_media(string $media_id)
    {
        $response = $this->whatsapp_app_cloud_api->downloadMedia($media_id);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_business_profile()
    {
        $response = $this->whatsapp_app_cloud_api->businessProfile('about');

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }

    public function test_update_business_profile()
    {
        $response = $this->whatsapp_app_cloud_api->updateBusinessProfile([
            'about' => 'About text',
            'email' => 'my-email@email.com',
        ]);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals(false, $response->isError());
    }
}
