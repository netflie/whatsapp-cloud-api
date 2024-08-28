![](https://netflie.es/wp-content/uploads/2022/05/whatsapp_cloud_api_banner-1.png)


## What It Does
This package makes it easy for developers to access [WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api "WhatsApp Cloud API") service in their PHP code.

The first **1,000 conversations** each month are free from WhatsApp Cloud API. A conversation.

## Getting Started
Please create and configure your Facebook WhatsApp application following the ["Get Stared"](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started) section of the official guide.

Minimum requirements – To run the SDK, your system will require **PHP >= 7.4** with a recent version of **CURL >=7.19.4** compiled with OpenSSL and zlib.

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
