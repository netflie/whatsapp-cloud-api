# Upgrade to v2

All instructions to upgrade this project from one major version to the next will be documented in this file. Upgrades must be run sequentially, meaning you should not skip major releases while upgrading (fix releases can be skipped).

## 1.x to 2.x

# Final classes
A lot of classes have been marked as final classes. If you have created new classes that extend any of them you will have to create new implementations. The reason for this change is to hide implementation details and avoid breaking versions in subsequent releases. Check the affected classes: https://github.com/netflie/whatsapp-cloud-api/commit/4cf094b1ff9a477eda34151a0e68fc7417950bbb

# Response errors
In previous versions when a request to WhatsApp servers failed a `GuzzleHttp\Exception\ClientException` exception was thrown. From now on a `Netflie\WhatsAppCloudApi\Response\ResponseException` exception will be thrown.

# Client
`Client::sendRequest(Request $request)` has been refactored to `Client::sendMessage(Request\RequestWithBody $request)`

# Request
Request class has been refactored: https://github.com/netflie/whatsapp-cloud-api/commit/17f76e90122d245aace6640a1f8766fb77c29ef6#diff-74d71c4d1f9d84b9b0d946ca96eb875274f95d60611611d84cc01cdf6ed04021L5

