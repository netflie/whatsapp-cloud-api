![](https://netflie.es/wp-content/uploads/2022/05/whatsapp_cloud_api_banner-1.png)


## What It Does
This package makes it easy for developers to access [WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api "WhatsApp Cloud API") service in their PHP code.

The first **1,000 conversations** each month are free from WhatsApp Cloud API. A conversation.

This package is in an **early stage** of development. New features will be added in the coming weeks. Be patient.

## Getting Started
Please create and configure your Facebook WhatsApp application following the ["Get Stared"](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started) section of the official guide.

Minimum requirements – To run the SDK, your system will require **PHP >= 7.0** with a recent version of **CURL >=7.19.4** compiled with OpenSSL and zlib.

## Installation
```compose require netflie/whatsapp-cloud-api ```

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

## Features

- Send Text Messages
- Send Documents

## Getting Help
- Ask a question on the [Discussions forum](https://github.com/netflie/whatsapp-cloud-api/discussions "Discussions forum")
- To report bugs, please [open an issue](https://github.com/netflie/whatsapp-cloud-api/issues/new/choose "open an issue")

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

Please see [CONTRIBUTING](https://github.com/netflie/.github/blob/main/CONTRIBUTING.md "CONTRIBUTING") for details.

## License

The MIT License (MIT). Please see License File for more information. Please see [License file](https://github.com/netflie/whatsapp-cloud-api/blob/main/LICENSE "License file") for more information.

## Disclaimer

This package is not officially maintained by Facebook. WhatsApp and Facebook trademarks and logos are the property of Meta Platforms, Inc.