# Upgrade Guide

All instructions to upgrade this project from one major version to the next will be documented in this file. Upgrades must be run sequentially, meaning you should not skip major releases while upgrading (fix releases can be skipped).

## 2.x to 3.x

### PHP version requirement

PHP 7.4 and PHP 8.0 are no longer supported. The minimum required PHP version is now **PHP 8.1**. Update your environment accordingly before upgrading.

### `MessageNotificationFactory` is now `final`

`Netflie\WhatsAppCloudApi\WebHook\Notification\MessageNotificationFactory` has been marked as `final`. If you have extended this class, you must refactor your code to use composition instead of inheritance.

### `Media` constructor signature changed

A new required `MediaType $type` parameter has been added to the `Media` constructor. If you are instantiating `Netflie\WhatsAppCloudApi\WebHook\Notification\Media` directly, you must pass a `Netflie\WhatsAppCloudApi\Message\Media\MediaType` instance as the eighth argument (before `$received_at_timestamp`).

Before:
```php
new Media($id, $business, $image_id, $mime_type, $sha256, $filename, $caption, $received_at_timestamp);
```

After:
```php
use Netflie\WhatsAppCloudApi\Message\Media\MediaType;

new Media($id, $business, $image_id, $mime_type, $sha256, $filename, $caption, new MediaType('image'), $received_at_timestamp);
```

### `Context` constructor signature changed

A new `bool $frequently_forwarded` parameter has been added to the `Context` constructor between `$forwarded` and `$referred_product`. If you are instantiating `Netflie\WhatsAppCloudApi\WebHook\Notification\Support\Context` directly, update your call accordingly.

Before:
```php
new Context($replying_to_message_id, $forwarded, $referred_product);
```

After:
```php
new Context($replying_to_message_id, $forwarded, $frequently_forwarded, $referred_product);
```

### `Referral` constructor signature changed

A new required `string $ctwa_clid` parameter has been appended to the `Referral` constructor. If you are instantiating `Netflie\WhatsAppCloudApi\WebHook\Notification\Support\Referral` directly, pass the Click to WhatsApp click ID as the last argument.

Before:
```php
new Referral($source_id, $source_url, $source_type, $headline, $body, $media_type, $media_url, $thumbnail_url);
```

After:
```php
new Referral($source_id, $source_url, $source_type, $headline, $body, $media_type, $media_url, $thumbnail_url, $ctwa_clid);
```

### New features

The following new features are available in 3.x:

#### Send Single Product Message

```php
$whatsapp_cloud_api->sendSingleProduct(
    '<destination-phone-number>',
    '<catalog-id>',
    '<product-sku-id>',
    'Optional body text',
    'Optional footer text'
);
```

#### Create and Update Templates

Template management now requires a `business_id` in the `WhatsAppCloudApi` constructor:

```php
$whatsapp = new WhatsAppCloudApi([
    'from_phone_number_id' => 'your-phone-number-id',
    'access_token'         => 'your-access-token',
    'business_id'          => 'your-business-id',
]);

// Create a template
$whatsapp->createTemplate('template_name', 'MARKETING', 'en_US', $components);

// Update a template by ID
$whatsapp->updateTemplateById('<template-id>', $payload);
```

#### Frequently forwarded messages

`MessageNotification` and `Context` now expose an `isFrequentlyForwarded()` method:

```php
$notification->isFrequentlyForwarded();
$notification->context()->isFrequentlyForwarded();
```

#### Media type in Webhook notifications

`Media` notifications now expose the media type via `type()`, which returns a `MediaType` enum value:

```php
$notification->type(); // returns a MediaType enum instance
```

#### Click to WhatsApp click ID in Referral

`Referral` now exposes the `ctwaClid()` method:

```php
$notification->referral()->ctwaClid();
```

#### `VerificationRequest` response code

`VerificationRequest::verify()` no longer calls `http_response_code()` when headers have already been sent. You can now read the resulting HTTP status code via the new `responseCode()` method:

```php
$verificationRequest = new VerificationRequest($verify_token);
$challenge = $verificationRequest->verify($payload);
$code = $verificationRequest->responseCode(); // 200 or 403
```

---

## 1.x to 2.x

# Final classes
A lot of classes have been marked as final classes. If you have created new classes that extend any of them you will have to create new implementations. The reason for this change is to hide implementation details and avoid breaking versions in subsequent releases. Check the affected classes: https://github.com/netflie/whatsapp-cloud-api/commit/4cf094b1ff9a477eda34151a0e68fc7417950bbb

# Response errors
In previous versions when a request to WhatsApp servers failed a `GuzzleHttp\Exception\ClientException` exception was thrown. From now on a `Netflie\WhatsAppCloudApi\Response\ResponseException` exception will be thrown.

# Client
`Client::sendRequest(Request $request)` has been refactored to `Client::sendMessage(Request\RequestWithBody $request)`

# Request
Request class has been refactored: https://github.com/netflie/whatsapp-cloud-api/commit/17f76e90122d245aace6640a1f8766fb77c29ef6#diff-74d71c4d1f9d84b9b0d946ca96eb875274f95d60611611d84cc01cdf6ed04021L5

