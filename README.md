![](https://netflie.es/wp-content/uploads/2022/05/whatsapp_cloud_api_banner-1.png)

## Table of Contents

1. [What It Does](#what-it-does)
2. [Getting Started](#getting-started)
3. [Installation](#installation)
4. [Quick Examples](#quick-examples)
   - [Send a text message](#send-a-text-message)
   - [Send a document](#send-a-document)
   - [Send a template message](#send-a-template-message)
   - [Send an audio message](#send-an-audio-message)
   - [Send an image message](#send-an-image-message)
   - [Send a video message](#send-a-video-message)
   - [Send a sticker message](#send-a-sticker-message)
   - [Send a location message](#send-a-location-message)
   - [Send a location request message](#send-a-location-request-message)
   - [Send a contact message](#send-a-contact-message)
   - [Send a list message](#send-a-list-message)
   - [Send a CTA URL message](#send-a-cta-url-message)
   - [Send a Catalog Message](#send-a-catalog-message)
   - [Send a button reply message](#send-a-button-reply-message)
   - [Replying messages](#replying-messages)
   - [React to a Message](#react-to-a-message)
5. [Media Messages](#media-messages)
   - [Upload media resources](#upload-media-resources)
   - [Download media resources](#download-media-resources)
6. [Message Response](#message-response)
7. [Webhooks](#webhooks)
   - [Webhook verification](#webhook-verification)
   - [Webhook notifications](#webhook-notifications)
   - [Mark a message as read](#mark-a-message-as-read)
8. [Business Profile](#business-profile)
   - [Get Business Profile](#get-business-profile)
   - [Update Business Profile](#update-business-profile)
   - [Get existing WhatsApp Templates](#get-existing-whatsapp-templates)
9. [Features](#features)
10. [Getting Help](#getting-help)
11. [Migration to v2](#migration-to-v2)
12. [Changelog](#changelog)
13. [Testing](#testing)
14. [Contributing](#contributing)
15. [License](#license)
16. [Disclaimer](#disclaimer)


## What It Does
This package makes it easy for developers to access [WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api "WhatsApp Cloud API") service in their PHP code.

The first **1,000 conversations** each month are free from WhatsApp Cloud API. A conversation.

## Getting Started
Please create and configure your Facebook WhatsApp application following the ["Get Stared"](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started) section of the official guide.

Minimum requirements – To run the SDK, your system will require **PHP >= 8.1** with a recent version of **CURL >=7.19.4** compiled with OpenSSL and zlib.

## Installation
```composer require netflie/whatsapp-cloud-api ```

## Quick Examples

### Send a text message
```php
<?php

// Require the Composer autoloader.
require 'vendor/autoload.php';

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

// Instantiate the WhatsAppCloudApi super class.
$whatsapp_cloud_api = new WhatsAppCloudApi([
    'from_phone_number_id' => 'your-configured-from-phone-number-id',
    'access_token' => 'your-facebook-whatsapp-application-token',
]);

$whatsapp_cloud_api->sendTextMessage('34676104574', 'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es');
```

### Send a document
You can send documents in two ways: by uploading a file to the WhatsApp Cloud servers (where you will receive an identifier) or from a link to a document published on internet.

```php
<?php

use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;

$document_id = '341476474779872';
$document_name = 'whatsapp-cloud-api-from-id.pdf';
$document_caption = 'WhastApp API Cloud Guide';

// With the Media Object ID of some document upload on the WhatsApp Cloud servers
$media_id = new MediaObjectID($document_id);
$whatsapp_cloud_api->sendDocument('34676104574', $media_id, $document_name, $document_caption);

// Or
$document_link = 'https://netflie.es/wp-content/uploads/2022/05/image.png';
$link_id = new LinkID($document_link);
$whatsapp_cloud_api->sendDocument('34676104574', $link_id, $document_name, $document_caption);
```

### Send a template message
```php
<?php

$whatsapp_cloud_api->sendTemplate('34676104574', 'hello_world', 'en_US'); // If not specified, Language will be default to en_US and otherwise it will be required.
```

You also can build templates with parameters:

```php
<?php

use Netflie\WhatsAppCloudApi\Message\Template\Component;

$component_header = [];

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

$components = new Component($component_header, $component_body, $component_buttons);
$whatsapp_cloud_api->sendTemplate('34676104574', 'sample_issue_resolution', 'en_US', $components); // Language is optional
```

### Send an audio message
```php
<?php

use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

$audio_link = 'https://netflie.es/wp-content/uploads/2022/05/file_example_OOG_1MG.ogg';
$link_id = new LinkID($audio_link);
$whatsapp_cloud_api->sendAudio('34676104574', $link_id);
```

### Send an image message
```php
<?php

use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;

$link_id = new LinkID('http(s)://image-url');
$whatsapp_cloud_api->sendImage('<destination-phone-number>', $link_id);

//or

$media_id = new MediaObjectID('<image-object-id>');
$whatsapp_cloud_api->sendImage('<destination-phone-number>', $media_id);
```

### Send a video message
```php
<?php

use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;

$link_id = new LinkID('http(s)://video-url');
$whatsapp_cloud_api->sendVideo('<destination-phone-number>', $link_id, '<video-caption>');

//or

$media_id = new MediaObjectID('<image-object-id>');
$whatsapp_cloud_api->sendVideo('<destination-phone-number>', $media_id, '<video-caption>');
```

### Send a sticker message

Stickers sample: https://github.com/WhatsApp/stickers

```php
<?php

use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\Media\MediaObjectID;

$link_id = new LinkID('http(s)://sticker-url');
$whatsapp_cloud_api->sendSticker('<destination-phone-number>', $link_id);

//or

$media_id = new MediaObjectID('<sticker-object-id>');
$whatsapp_cloud_api->sendSticker('<destination-phone-number>', $media_id);
```

### Send a location message

```php
<?php

$whatsapp_cloud_api->sendLocation('<destination-phone-number>', $longitude, $latitude, $name, $address);
```

### Send a location request message

```php
<?php

$body = 'Let\'s start with your pickup. You can either manually *enter an address* or *share your current location*.';
$whatsapp_cloud_api->sendLocationRequest('<destination-phone-number>', $body);
```

### Send a contact message

```php
<?php

use Netflie\WhatsAppCloudApi\Message\Contact\ContactName;
use Netflie\WhatsAppCloudApi\Message\Contact\Phone;
use Netflie\WhatsAppCloudApi\Message\Contact\PhoneType;

$name = new ContactName('Adams', 'Smith');
$phone = new Phone('34676204577', PhoneType::CELL());

$whatsapp_cloud_api->sendContact('<destination-phone-number>', $name, $phone);
```

### Send a list message

```php
<?php

use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;

$rows = [
    new Row('1', '⭐️', "Experience wasn't good enough"),
    new Row('2', '⭐⭐️', "Experience could be better"),
    new Row('3', '⭐⭐⭐️', "Experience was ok"),
    new Row('4', '⭐⭐️⭐⭐', "Experience was good"),
    new Row('5', '⭐⭐️⭐⭐⭐️', "Experience was excellent"),
];
$sections = [new Section('Stars', $rows)];
$action = new Action('Submit', $sections);

$whatsapp_cloud_api->sendList(
    '<destination-phone-number>',
    'Rate your experience',
    'Please consider rating your shopping experience in our website',
    'Thanks for your time',
    $action
);
```

### Send a CTA URL message

```php
<?php

use Netflie\WhatsAppCloudApi\Message\CtaUrl\TitleHeader;

$header = new TitleHeader('Booking');

$whatsapp_cloud_api->sendCtaUrl(
    '<destination-phone-number>',
    'See Dates',
    'https://www.example.com',
    $header,
    'Tap the button below to see available dates.',
    'Dates subject to change.',
);
```

### Send Catalog Message

```php
<?php

$body = 'Hello! Thanks for your interest. Ordering is easy. Just visit our catalog and add items you\'d like to purchase.';
$footer = 'Best grocery deals on WhatsApp!';
$sku_thumbnail = '<product-sku-id>'; // product sku id to use as header thumbnail

$whatsapp_cloud_api->sendCatalog(
    '<destination-phone-number>',
    $body,
    $footer, // optional
    $sku_thumbnail // optional
);
```

### Send a button reply message

```php
<?php

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;

$whatsapp_cloud_api = new WhatsAppCloudApi([
  'from_phone_number_id' => 'your-configured-from-phone-number-id',
  'access_token' => 'your-facebook-whatsapp-application-token' 
]);

$rows = [
    new Button('button-1', 'Yes'),
    new Button('button-2', 'No'),
    new Button('button-3', 'Not Now'),
];
$action = new ButtonAction($rows);

$whatsapp_cloud_api->sendButton(
    '<destination-phone-number>',
    'Would you like to rate us on Trustpilot?',
    $action,
    'RATE US', // Optional: Specify a header (type "text")
    'Please choose an option' // Optional: Specify a footer 
);
```

### Send Multi Product Message
```php
<?php

use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\MultiProduct\Row;
use Netflie\WhatsAppCloudApi\Message\MultiProduct\Section;
use Netflie\WhatsAppCloudApi\Message\MultiProduct\Action;

$rows_section_1 = [
    new Row('<product-sku-id>'),
    new Row('<product-sku-id>'),
    // etc
];

$rows_section_2 = [
    new Row('<product-sku-id>'),
    new Row('<product-sku-id>'),
    new Row('<product-sku-id>'),
    // etc
];

$sections = [
    new Section('Section 1', $rows_section_1),
    new Section('Section 2', $rows_section_2),
];

$action = new Action($sections);
$catalog_id = '<catalog-id>';
$header = 'Grocery Collections';
$body = 'Hello! Thanks for your interest. Here\'s what we can offer you under our grocery collection. Thank you for shopping with us.';
$footer = 'Subject to T&C';

$whatsapp_cloud_api->sendMultiProduct(
    '<destination-phone-number>',
    $catalog_id,
    $action,
    $header,
    $body,
    $footer // optional
);
```

### Send Single Product Message
```php
<?php

$catalog_id = '<catalog-id>';
$sku_id = '<product-sku-id>';
$body = 'Hello! Here\'s your requested product. Thanks for shopping with us.';
$footer = 'Subject to T&C';

$whatsapp_cloud_api->sendSingleProduct(
    '<destination-phone-number>',
    $catalog_id,
    $sku_id,
    $body, // body: optional
    $footer // footer: optional
);
```

### Replying messages

You can reply a previous sent message:

```php
<?php

$whatsapp_cloud_api
    ->replyTo('<whatsapp-message-id-to-reply>')
    ->sendTextMessage(
        '34676104574',
        'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es'
    );
```

### React to a Message

You can react to a message from your conversations if you know the messageid

```php
<?php

$whatsapp_cloud_api->sendReaction(
        '<destination-phone-number>',
        '<message-id-to-react-to>',
        '👍', // the emoji
    );

// Unreact to a message
$whatsapp_cloud_api->sendReaction(
        '<destination-phone-number>',
        '<message-id-to-unreact-to>'
    );

```

## Media messages
### Upload media resources
Media messages accept as identifiers an Internet URL pointing to a public resource (image, video, audio, etc.). When you try to send a media message from a URL you must instantiate the `LinkID` object.

You can also upload your media resources to WhatsApp servers and you will receive a resource identifier:

```php
$response = $whatsapp_cloud_api->uploadMedia('my-image.png');

$media_id = new MediaObjectID($response->decodedBody()['id']);
$whatsapp_cloud_api->sendImage('<destination-phone-number>', $media_id);

```

### Download media resources
To download a media resource:

```php
$response = $whatsapp_cloud_api->downloadMedia('<media-id>');
```


## Message Response
WhatsAppCloudApi instance returns a Response class or a ResponseException if WhatsApp servers return an error.

```php
try {
    $response = $this->whatsapp_app_cloud_api->sendTextMessage(
        '<destination-phone-number>,
        'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es',
        true
    );
} catch (\Netflie\WhatsAppCloudApi\Response\ResponseException $e) {
    print_r($e->response()); // You can still check the Response returned from Meta servers
}
```

## Webhooks

### Webhook verification
Add your webhook in your Meta App dashboard. You need to verify your webhook:

```php
<?php
require 'vendor/autoload.php';

use Netflie\WhatsAppCloudApi\WebHook;

// Instantiate the WhatsAppCloudApi super class.
$webhook = new WebHook();

echo $webhook->verify($_GET, "<the-verify-token-defined-in-your-app-dashboard>");
```

### Webhook notifications
Webhook is now verified, you will start receiving notifications every time your customers send messages.


```php
<?php
require 'vendor/autoload.php';
define('STDOUT', fopen('php://stdout', 'w'));

use Netflie\WhatsAppCloudApi\WebHook;


$payload = file_get_contents('php://input');
fwrite(STDOUT, print_r($payload, true) . "\n");

// Instantiate the Webhook super class.
$webhook = new WebHook();

// Read the first message
fwrite(STDOUT, print_r($webhook->read(json_decode($payload, true)), true) . "\n");

//Read all messages in case Meta decided to batch them
fwrite(STDOUT, print_r($webhook->readAll(json_decode($payload, true)), true) . "\n");
```

The `Webhook::read` function will return a `Notification` instance. Please, [explore](https://github.com/netflie/whatsapp-cloud-api/tree/main/src/WebHook/Notification "explore") the different notifications availables.

### Mark a message as read
When you receive an incoming message from Webhooks, you can mark the message as read by changing its status to read. Messages marked as read display two blue check marks alongside their timestamp.

Marking a message as read will also mark earlier messages in the conversation as read.

```php
<?php

$whatsapp_cloud_api->markMessageAsRead('<message-id>');
```

## Business profile
To use the following functionalities, pass a valid WhatsApp `business_id` to the `WhatsAppCloudApi` config. Example:

```php
<?php
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

$config = [
        'from_phone_number_id' => null,
        'access_token' => '',
        'business_id' => '', // this value is required to make the following calls work
        'graph_version' => 'v18.0',
        'client_handler' => null,
        'timeout' => null,
    ];
$whatsapp_cloud_api = new WhatsAppCloudApi($config);

```

Please also see the full [WhatsApp Business Management API reference](https://developers.facebook.com/docs/whatsapp/business-management-api).

### Get Business Profile
```php
<?php

$whatsapp_cloud_api->businessProfile('<fields>');
```

### Update Business Profile
```php
<?php

$whatsapp_cloud_api->updateBusinessProfile([
    'about' => '<about_text>',
    'email' => '<email>'
]);
```

Fields list: https://developers.facebook.com/docs/whatsapp/cloud-api/reference/business-profiles

### Get existing WhatsApp Templates
You can retrieve an array of existing templates using the `messageTemplates` method. This method requires the `fields` parameter, which is a string with a comma seperated list of the [WhatsApp template fields](https://developers.facebook.com/docs/graph-api/reference/whats-app-business-hsm/#fields "WhatsApp Template Fields docs") you want. 

You can optionally filter on template `status` and set a `limit`.

```php
<?php
use Netflie\WhatsAppCloudApi\Enums\TemplateCategoryEnum;

$templates = $whatsapp_cloud_api->messageTemplates(
    fields: 'name,status,id', // comma seperated fields
    status: TemplateCategoryEnum::APPROVED, // leave empty to get all templates
    limit: 10, // leave empty for the default limit of 50
);
```

This results in an array of templates, example result:
```php
// value of $templates
$templates = [
    [
        "name" => "seasonal_promotion_text_only",
        "status" => "APPROVED",
        "id" => "564750795574598"
    ],
    [
        "name" => "seasonal_promotion_video",
        "status" => "APPROVED",
        "id" => "1252715608684590"
    ],
    [
        "name" => "seasonal_promotion_image_header",
        "status" => "APPROVED",
        "id" => "1372429296936443"
    ]
];
```

## Features

- Send Text Messages
- Send Documents
- Send Templates with parameters
- Send Audios
- Send Images
- Send Videos
- Send Stickers
- Send Locations
- Send Location Request
- Send Contacts
- Send Lists
- Send Buttons
- Send Multi Product Message
- Send Single Product
- Upload media resources to WhatsApp servers
- Download media resources from WhatsApp servers
- Mark messages as read
- React to a Message
- Get/Update Business Profile
- Get existing whatsapp templates
- Webhook verification
- Webhook notifications

## Getting Help
- Ask a question on the [Discussions forum](https://github.com/netflie/whatsapp-cloud-api/discussions "Discussions forum")
- To report bugs, please [open an issue](https://github.com/netflie/whatsapp-cloud-api/issues/new/choose "open an issue")

## Migration to v2

Please see [UPGRADE](https://github.com/netflie/whatsapp-cloud-api/blob/main/UPGRADE.md "UPGRADE") for more information on how to upgrade to v2.

## Changelog

Please see [CHANGELOG](https://github.com/netflie/whatsapp-cloud-api/blob/main/CHANGELOG.md "CHANGELOG") for more information what has changed recently.

## Testing
```php
composer unit-test
```
You also can run tests making real calls to the WhastApp Clou API. Please put your testing credentials on **WhatsAppCloudApiTestConfiguration** file.
```php
composer integration-test
```
## Contributing

Please see [CONTRIBUTING](https://github.com/netflie/.github/blob/master/CONTRIBUTING.md "CONTRIBUTING") for details.

## License

The MIT License (MIT). Please see License File for more information. Please see [License file](https://github.com/netflie/whatsapp-cloud-api/blob/main/LICENSE "License file") for more information.

## Disclaimer

This package is not officially maintained by Facebook. WhatsApp and Facebook trademarks and logos are the property of Meta Platforms, Inc.
