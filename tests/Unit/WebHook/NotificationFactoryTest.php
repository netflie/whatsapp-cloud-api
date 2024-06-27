<?php

namespace Netflie\WhatsAppCloudApi\Tests\Unit\WebHook;

use Netflie\WhatsAppCloudApi\WebHook\Notification;
use Netflie\WhatsAppCloudApi\WebHook\NotificationFactory;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
final class NotificationFactoryTest extends TestCase
{
    private NotificationFactory $notification_factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->notification_factory = new NotificationFactory();
    }

    public function test_build_from_payload_can_build_a_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "WHATSAPP_ID"
                        }],
                      "messages": [{
                          "context": {
                            "from": "PHONE_NUMBER",
                            "id": "wamid.ID",
                            "forwarded": true,
                            "referred_product": {
                              "catalog_id": "CATALOG_ID",
                              "product_retailer_id": "PRODUCT_ID"
                            }
                          },
                          "referral": {
                            "source_url": "AD_OR_POST_FB_URL",
                            "source_id": "ADID",
                            "source_type": "ad or post",
                            "headline": "AD_TITLE",
                            "body": "AD_DESCRIPTION",
                            "media_type": "image or video",
                            "image_url": "RAW_IMAGE_URL",
                            "video_url": "RAW_VIDEO_URL",
                            "thumbnail_url": "RAW_THUMBNAIL_URL"
                          },
                          "from": "16315551234",
                          "id": "wamid.ID",
                          "timestamp": 1669233778,
                          "type": "button",
                          "button": {
                            "text": "No",
                            "payload": "No-Button-Payload"
                          }
                        }]
                  },
                  "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertEquals('wamid.ID', $notification->replyingToMessageId());
        $this->assertEquals('PHONE_NUMBER_ID', $notification->businessPhoneNumberId());
        $this->assertEquals('PHONE_NUMBER', $notification->businessPhoneNumber());
        $this->assertTrue($notification->isForwarded());
        $this->assertEquals('WHATSAPP_ID', $notification->customer()->id());
        $this->assertEquals('NAME', $notification->customer()->name());
        $this->assertEquals('ADID', $notification->referral()->sourceId());
        $this->assertEquals('AD_OR_POST_FB_URL', $notification->referral()->sourceUrl());
        $this->assertEquals('ad or post', $notification->referral()->sourceType());
        $this->assertEquals('AD_TITLE', $notification->referral()->headline());
        $this->assertEquals('AD_DESCRIPTION', $notification->referral()->body());
        $this->assertEquals('image or video', $notification->referral()->mediaType());
        $this->assertEquals('RAW_IMAGE_URL', $notification->referral()->mediaUrl());
        $this->assertEquals('RAW_THUMBNAIL_URL', $notification->referral()->thumbnailUrl());
        $this->assertEquals('CATALOG_ID', $notification->context()->catalogId());
        $this->assertEquals('PRODUCT_ID', $notification->context()->productRetailerId());
    }

    public function test_build_from_payload_can_build_a_text_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "PHONE_NUMBER"
                        }],
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "timestamp": "1669233778",
                          "text": {
                            "body": "MESSAGE_BODY"
                          },
                          "type": "text"
                        }]
                  },
                  "field": "messages"
                }]
          }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Text::class, $notification);
        $this->assertEquals('MESSAGE_BODY', $notification->message());
    }

    public function test_build_from_payload_can_build_multiple_text_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "PHONE_NUMBER"
                        }],
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "timestamp": "1669233778",
                          "text": {
                            "body": "MESSAGE_BODY"
                          },
                          "type": "text"
                        }]
                  },
                  "field": "messages"
                },
                {
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "PHONE_NUMBER"
                        }],
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "timestamp": "1669233779",
                          "text": {
                            "body": "MESSAGE_BODY2"
                          },
                          "type": "text"
                        }]
                  },
                  "field": "messages"
                }]
          }]
        }', true);

        $notifications = $this->notification_factory->buildAllFromPayload($payload);

        $this->assertCount(2, $notifications);

        $this->assertInstanceOf(Notification\Text::class, $notifications[0]);
        $this->assertInstanceOf(Notification\Text::class, $notifications[1]);
        $this->assertEquals('MESSAGE_BODY', $notifications[0]->message());
        $this->assertEquals('MESSAGE_BODY2', $notifications[1]->message());
    }

    public function test_build_from_payload_can_build_a_reaction_notification()
    {
        $payload = json_decode('{
            "object": "whatsapp_business_account",
            "entry": [{
                "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
                "changes": [{
                    "value": {
                        "messaging_product": "whatsapp",
                        "metadata": {
                            "display_phone_number": "PHONE_NUMBER",
                            "phone_number_id": "PHONE_NUMBER_ID"
                        },
                        "contacts": [{
                            "profile": {
                              "name": "NAME"
                            },
                            "wa_id": "PHONE_NUMBER"
                          }],
                        "messages": [{
                            "from": "PHONE_NUMBER",
                            "id": "wamid.ID",
                            "timestamp": "1669233778",
                            "reaction": {
                              "message_id": "MESSAGE_ID",
                              "emoji": "EMOJI"
                            },
                            "type": "reaction"
                          }]
                    },
                    "field": "messages"
                  }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Reaction::class, $notification);
        $this->assertEquals('MESSAGE_ID', $notification->messageId());
        $this->assertEquals('EMOJI', $notification->emoji());
    }

    public function test_build_from_payload_can_build_a_removed_reaction_notification()
    {
        $payload = json_decode('{
            "object": "whatsapp_business_account",
            "entry": [{
                "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
                "changes": [{
                    "value": {
                        "messaging_product": "whatsapp",
                        "metadata": {
                            "display_phone_number": "PHONE_NUMBER",
                            "phone_number_id": "PHONE_NUMBER_ID"
                        },
                        "contacts": [{
                            "profile": {
                              "name": "NAME"
                            },
                            "wa_id": "PHONE_NUMBER"
                          }],
                        "messages": [{
                            "from": "PHONE_NUMBER",
                            "id": "wamid.ID",
                            "timestamp": "1669233778",
                            "reaction": {
                              "message_id": "MESSAGE_ID"
                            },
                            "type": "reaction"
                          }]
                    },
                    "field": "messages"
                  }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Reaction::class, $notification);
        $this->assertEquals('MESSAGE_ID', $notification->messageId());
        $this->assertEquals('', $notification->emoji());
    }

    public function test_build_from_payload_can_build_an_image_notification()
    {
        $payload = json_decode('{
            "object": "whatsapp_business_account",
            "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "WHATSAPP_ID"
                        }],
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "timestamp": "1669233778",
                          "type": "image",
                          "image": {
                            "caption": "CAPTION_TEXT",
                            "mime_type": "image/jpeg",
                            "sha256": "IMAGE_HASH",
                            "id": "IMAGE_ID"
                          }
                        }]
                  },
                  "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Media::class, $notification);
        $this->assertEquals('IMAGE_ID', $notification->imageId());
        $this->assertEquals('IMAGE_HASH', $notification->sha256());
        $this->assertEquals('image/jpeg', $notification->mimeType());
        $this->assertEquals('CAPTION_TEXT', $notification->caption());
    }

    public function test_build_from_payload_can_build_an_document_notification()
    {
        $payload = json_decode('{
            "object": "whatsapp_business_account",
            "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "WHATSAPP_ID"
                        }],
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "timestamp": "1669233778",
                          "type": "document",
                          "document": {
                            "caption": "CAPTION_TEXT",
                            "filename": "FILENAME",
                            "mime_type": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                            "sha256": "DOCUMENT_HASH",
                            "id": "IMAGE_ID"
                          }
                        }]
                  },
                  "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Media::class, $notification);
        $this->assertEquals('IMAGE_ID', $notification->imageId());
        $this->assertEquals('DOCUMENT_HASH', $notification->sha256());
        $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $notification->mimeType());
        $this->assertEquals('CAPTION_TEXT', $notification->caption());
        $this->assertEquals('FILENAME', $notification->filename());
    }

    public function test_build_from_payload_can_build_a_sticker_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "ID",
              "changes": [
                {
                  "value": {
                    "messaging_product": "whatsapp",
                    "metadata": {
                      "display_phone_number": "PHONE_NUMBER",
                      "phone_number_id": "PHONE_NUMBER_ID"
                    },
                    "contacts": [
                      {
                        "profile": {
                          "name": "NAME"
                        },
                        "wa_id": "ID"
                      }
                    ],
                    "messages": [
                      {
                        "from": "SENDER_PHONE_NUMBER",
                        "id": "wamid.ID",
                        "timestamp": "1669233778",
                        "type": "sticker",
                        "sticker": {
                          "mime_type": "image/webp",
                          "sha256": "STICKER_HASH",
                          "id": "STICKER_ID"
                        }
                      }
                    ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Media::class, $notification);
        $this->assertEquals('STICKER_ID', $notification->imageId());
        $this->assertEquals('STICKER_HASH', $notification->sha256());
        $this->assertEquals('image/webp', $notification->mimeType());
    }

    public function test_build_from_payload_can_build_an_unknown_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                        "display_phone_number": "PHONE_NUMBER",
                        "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "WHATSAPP_ID"
                        }],
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "timestamp": "1669233778",
                          "errors": [
                            {
                              "code": 131051,
                              "details": "Message type is not currently supported",
                              "title": "Unsupported message type"
                            }],
                           "type": "unknown"
                           }]
                    },
                    "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Unknown::class, $notification);
    }

    public function test_build_from_payload_can_build_a_location_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "WHATSAPP_ID"
                        }],
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "timestamp": "1669233778",
                          "type": "location",
                          "location": {
                            "latitude": "LOCATION_LATITUDE",
                            "longitude": "LOCATION_LONGITUDE",
                            "name": "LOCATION_NAME",
                            "address": "LOCATION_ADDRESS"
                          }
                        }]
                  },
                  "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Location::class, $notification);
        $this->assertEquals('LOCATION_LATITUDE', $notification->latitude());
        $this->assertEquals('LOCATION_LONGITUDE', $notification->longitude());
        $this->assertEquals('LOCATION_NAME', $notification->name());
        $this->assertEquals('LOCATION_ADDRESS', $notification->address());
    }

    public function test_build_from_payload_can_build_a_contact_notification()
    {
        $payload = json_decode('{
          "object":"whatsapp_business_account",
          "entry":[{
            "id":"WHATSAPP_BUSINESS_ACCOUNT_ID",
            "changes":[{
              "value":{
                "messaging_product":"whatsapp",
                "metadata": {
                  "display_phone_number":"PHONE_NUMBER",
                  "phone_number_id":"PHONE_NUMBER_ID"
                  },
                "contacts": [{
                  "profile":{
                    "name":"NAME"
                    },
                  "wa_id":"WHATSAPP_ID"
                  }],
                "messages":[{
                  "from":"PHONE_NUMBER",
                  "id":"wamid.ID",
                  "timestamp":"1669233778",
                  "type": "contacts",
                  "contacts":[{
                    "addresses":[{
                      "city":"CONTACT_CITY",
                      "country":"CONTACT_COUNTRY",
                      "country_code":"CONTACT_COUNTRY_CODE",
                      "state":"CONTACT_STATE",
                      "street":"CONTACT_STREET",
                      "type":"HOME or WORK",
                      "zip":"CONTACT_ZIP"
                    }],
                    "birthday":"1989-03-16",
                    "emails":[{
                      "email":"CONTACT_EMAIL",
                      "type":"WORK"
                      }],
                    "name":{
                      "formatted_name":"CONTACT_FORMATTED_NAME",
                      "first_name":"CONTACT_FIRST_NAME",
                      "last_name":"CONTACT_LAST_NAME",
                      "middle_name":"CONTACT_MIDDLE_NAME",
                      "suffix":"CONTACT_SUFFIX",
                      "prefix":"CONTACT_PREFIX"
                      },
                    "org":{
                      "company":"CONTACT_ORG_COMPANY",
                      "department":"CONTACT_ORG_DEPARTMENT",
                      "title":"CONTACT_ORG_TITLE"
                      },
                    "phones":[{
                      "phone":"CONTACT_PHONE",
                      "wa_id":"CONTACT_WA_ID",
                      "type":"HOME or WORK>"
                      }],
                    "urls":[{
                      "url":"CONTACT_URL",
                      "type":"HOME or WORK"
                      }]
                    }]
                  }]
                },
              "field":"messages"
            }]
          }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Contact::class, $notification);
        $this->assertIsArray($notification->name());
        $this->assertEquals('CONTACT_FORMATTED_NAME', $notification->formattedName());
        $this->assertEquals('CONTACT_FIRST_NAME', $notification->firstName());
        $this->assertEquals('CONTACT_LAST_NAME', $notification->lastName());
        $this->assertEquals('CONTACT_MIDDLE_NAME', $notification->middleName());
        $this->assertIsArray($notification->addresses());
        $this->assertInstanceOf(\DateTimeImmutable::class, $notification->birthday());
        $this->assertIsArray($notification->emails());
        $this->assertEquals('CONTACT_EMAIL', $notification->emails()[0]['email']);
        $this->assertEquals('WORK', $notification->emails()[0]['type']);
        $this->assertIsArray($notification->company());
        $this->assertEquals('CONTACT_ORG_COMPANY', $notification->companyName());
        $this->assertEquals('CONTACT_ORG_DEPARTMENT', $notification->companyDepartment());
        $this->assertEquals('CONTACT_ORG_TITLE', $notification->companyTitle());
        $this->assertIsArray($notification->phones());
        $this->assertEquals('CONTACT_PHONE', $notification->phones()[0]['phone']);
        $this->assertIsArray($notification->urls());
        $this->assertEquals('CONTACT_URL', $notification->urls()[0]['url']);
    }

    public function test_build_from_payload_can_build_a_button_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "WHATSAPP_ID"
                        }],
                      "messages": [{
                          "context": {
                            "from": "PHONE_NUMBER",
                            "id": "wamid.ID"
                          },
                          "from": "16315551234",
                          "id": "wamid.ID",
                          "timestamp": 1669233778,
                          "type": "button",
                          "button": {
                            "text": "No",
                            "payload": "No-Button-Payload"
                          }
                        }]
                  },
                  "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Button::class, $notification);
        $this->assertEquals('No', $notification->text());
        $this->assertEquals('No-Button-Payload', $notification->payload());
    }

    public function test_build_from_payload_can_build_a_list_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [
                {
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                           "display_phone_number": "PHONE_NUMBER",
                           "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [
                        {
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "PHONE_NUMBER_ID"
                        }
                      ],
                      "messages": [
                        {
                          "from": "PHONE_NUMBER_ID",
                          "id": "wamid.ID",
                          "timestamp": 1669233778,
                          "interactive": {
                            "list_reply": {
                              "id": "list_reply_id",
                              "title": "list_reply_title",
                              "description": "list_reply_description"
                            },
                            "type": "list_reply"
                          },
                          "type": "interactive"
                        }
                      ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Interactive::class, $notification);
        $this->assertEquals('list_reply_id', $notification->itemId());
        $this->assertEquals('list_reply_title', $notification->title());
        $this->assertEquals('list_reply_description', $notification->description());
    }

    public function test_build_from_payload_can_build_a_button_reply_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [
                {
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                           "display_phone_number": "PHONE_NUMBER",
                           "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [
                        {
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "PHONE_NUMBER_ID"
                        }
                      ],
                      "messages": [
                        {
                          "from": "PHONE_NUMBER_ID",
                          "id": "wamid.ID",
                          "timestamp": 1669233778,
                          "interactive": {
                            "button_reply": {
                              "id": "unique-button-identifier-here",
                              "title": "button-text"
                            },
                            "type": "button_reply"
                          },
                          "type": "interactive"
                        }
                      ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Interactive::class, $notification);
        $this->assertEquals('unique-button-identifier-here', $notification->itemId());
        $this->assertEquals('button-text', $notification->title());
        $this->assertEquals('', $notification->description());
    }

    public function test_build_from_payload_can_build_a_flow_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [
                {
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                           "display_phone_number": "PHONE_NUMBER",
                           "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [
                        {
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "PHONE_NUMBER_ID"
                        }
                      ],
                      "messages": [
                        {
                          "from": "PHONE_NUMBER_ID",
                          "id": "wamid.ID",
                          "timestamp": 1669233778,
                          "interactive": {
                            "type": "nfm_reply",
                            "nfm_reply": {
                              "response_json": "{\"screen_0_name_0\":\"Email\",\"screen_0_orderNumber_1\":\"ID\",\"flow_token\":\"unused\"}",
                              "body": "Sent",
                              "name": "flow"
                            }
                          },
                          "type": "interactive"
                        }
                      ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Flow::class, $notification);
        $this->assertEquals('flow', $notification->name());
        $this->assertEquals('Sent', $notification->body());
        $this->assertEquals('{"screen_0_name_0":"Email","screen_0_orderNumber_1":"ID","flow_token":"unused"}', $notification->response());
    }

    public function test_build_from_payload_can_build_an_order_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [
                {
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                           "display_phone_number": "PHONE_NUMBER",
                           "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [
                        {
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "PHONE_NUMBER_ID"
                        }
                      ],
                      "messages": [
                        {
                          "from": "PHONE_NUMBER_ID",
                          "id": "wamid.ID",
                          "timestamp": 1669233778,
                          "order": {
                            "catalog_id": "the-catalog_id",
                            "product_items": [
                              {
                                "product_retailer_id":"the-product-SKU-identifier",
                                "quantity":"number-of-item",
                                "item_price":"unitary-price-of-item",
                                "currency":"price-currency"
                              },
                              {
                                "product_retailer_id":"the-product-SKU-identifier-2",
                                "quantity":"number-of-item",
                                "item_price":"unitary-price-of-item",
                                "currency":"price-currency"
                              }
                            ],
                            "text":"text-message-sent-along-with-the-order"
                          },
                          "type": "order"
                        }
                      ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\Order::class, $notification);
        $this->assertEquals('the-catalog_id', $notification->catalogId());
        $this->assertEquals('text-message-sent-along-with-the-order', $notification->message());
        $this->assertEquals('the-product-SKU-identifier', $notification->products()->productRetailerId());
        $this->assertEquals('number-of-item', $notification->products()->quantity());
        $this->assertEquals('unitary-price-of-item', $notification->products()->price());
        $this->assertEquals('price-currency', $notification->products()->currency());
    }

    public function test_build_from_payload_can_build_a_system_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "NEW_PHONE_NUMBER_ID"
                      },
                      "messages": [{
                          "from": "PHONE_NUMBER",
                          "id": "wamid.ID",
                          "system": {
                            "body": "NAME changed from PHONE_NUMBER to PHONE_NUMBER",
                            "wa_id": "NEW_PHONE_NUMBER_ID",
                            "type": "user_changed_number",
                            "customer": "OLD_PHONE_NUMBER_ID"
                          },
                          "timestamp": 1669233778,
                          "type": "system"
                        }]
                  },
                  "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\System::class, $notification);
        $this->assertEquals('NEW_PHONE_NUMBER_ID', $notification->businessPhoneNumberId());
        $this->assertEquals('OLD_PHONE_NUMBER_ID', $notification->oldBusinessPhoneNumberId());
    }

    public function test_build_from_payload_can_build_a_status_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "114957184830690",
              "changes": [
                {
                  "value": {
                    "messaging_product": "whatsapp",
                    "metadata": {
                      "display_phone_number": "CUSTOMER_PHONE_NUMBER",
                      "phone_number_id": "CUSTOMER_PHONE_NUMBER"
                    },
                    "statuses": [
                      {
                        "id": "wamid.ID",
                        "status": "read",
                        "timestamp": "1674914356",
                        "recipient_id": "CUSTOMER_PHONE_NUMBER"
                      }
                    ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\StatusNotification::class, $notification);
        $this->assertEquals('wamid.ID', $notification->id());
        $this->assertEquals('CUSTOMER_PHONE_NUMBER', $notification->customerId());
        $this->assertNull($notification->isBusinessInitiatedConversation());
        $this->assertNull($notification->isCustomerInitiatedConversation());
        $this->assertNull($notification->isReferralInitiatedConversation());
        $this->assertEquals('read', $notification->status());
        $this->assertTrue($notification->isMessageRead());
        $this->assertTrue($notification->isMessageDelivered());
        $this->assertTrue($notification->isMessageSent());
    }

    public function test_build_from_payload_can_build_a_status_conversation_initiated_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
            "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
            "changes": [{
            "value": {
            "messaging_product": "whatsapp",
            "metadata": {
              "display_phone_number": "PHONE_NUMBER",
              "phone_number_id": "PHONE_NUMBER_ID"
              },
            "statuses": [{
              "id": "wamid.ID",
              "recipient_id": "CUSTOMER_PHONE_NUMBER",
              "status": "read",
              "timestamp": "1669233778",
              "conversation": {
                "id": "CONVERSATION_ID",
                "expiration_timestamp": 1669233778,
                "origin": {
                  "type": "user_initiated"
                  }
                }
              }]
            },
            "field": "messages"
          }]
         }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\StatusNotification::class, $notification);
        $this->assertEquals('wamid.ID', $notification->id());
        $this->assertEquals('CUSTOMER_PHONE_NUMBER', $notification->customerId());
        $this->assertEquals('CONVERSATION_ID', $notification->conversationId());
        $this->assertEquals('1669233778', $notification->conversationExpiresAt()->getTimestamp());
        $this->assertFalse($notification->isBusinessInitiatedConversation());
        $this->assertTrue($notification->isCustomerInitiatedConversation());
        $this->assertFalse($notification->isReferralInitiatedConversation());
        $this->assertEquals('read', $notification->status());
        $this->assertTrue($notification->isMessageRead());
        $this->assertTrue($notification->isMessageDelivered());
        $this->assertTrue($notification->isMessageSent());
    }

    public function test_build_from_payload_can_build_a_status_notification_without_expiration_time()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
            "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
            "changes": [{
            "value": {
            "messaging_product": "whatsapp",
            "metadata": {
              "display_phone_number": "PHONE_NUMBER",
              "phone_number_id": "PHONE_NUMBER_ID"
              },
            "statuses": [{
              "id": "wamid.ID",
              "recipient_id": "CUSTOMER_PHONE_NUMBER",
              "status": "delivered",
              "timestamp": "1669233778",
              "conversation": {
                "id": "CONVERSATION_ID",
                "origin": {
                  "type": "user_initiated"
                  }
                }
              }]
            },
            "field": "messages"
          }]
         }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertNull($notification->conversationExpiresAt());
    }

    public function test_build_from_payload_can_build_a_status_with_pricing_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
            "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
            "changes": [{
              "value": {
                "messaging_product": "whatsapp",
                "metadata": {
                  "display_phone_number": "PHONE_NUMBER",
                  "phone_number_id": "PHONE_NUMBER_ID"
                },
                "statuses": [{
                  "id": "wamid.ID",
                  "recipient_id": "CUSTOMER_PHONE_NUMBER",
                  "status": "delivered",
                  "timestamp": "1690327464",
                  "conversation": {
                    "id": "CONVERSATION_ID",
                    "origin": {
                      "type": "marketing"
                    }
                  },
                  "pricing": {
                    "billable": true,
                    "pricing_model": "CBP",
                    "category": "marketing"
                  }
                }]
              },
              "field": "messages"
            }]
          }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\StatusNotification::class, $notification);
        $this->assertEquals('wamid.ID', $notification->id());
        $this->assertEquals('CUSTOMER_PHONE_NUMBER', $notification->customerId());
        $this->assertEquals('marketing', $notification->pricingCategory());
        $this->assertEquals('CBP', $notification->pricingModel());
        $this->assertTrue($notification->isBillable());
        $this->assertEquals('CONVERSATION_ID', $notification->conversationId());
        $this->assertEquals('delivered', $notification->status());
        $this->assertFalse($notification->isMessageRead());
        $this->assertTrue($notification->isMessageDelivered());
        $this->assertTrue($notification->isMessageSent());
    }

    public function test_build_from_payload_can_build_a_status_notification_with_errors()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "114957184830690",
              "changes": [
                {
                  "value": {
                    "messaging_product": "whatsapp",
                    "metadata": {
                      "display_phone_number": "15550483457",
                      "phone_number_id": "102944729380254"
                    },
                    "statuses": [
                      {
                        "id": "amid.ID",
                        "status": "failed",
                        "timestamp": "1674912647",
                        "recipient_id": "CUSTOMER_PHONE_NUMBER",
                        "errors": [
                          {
                            "code": 131053,
                            "title": "ERROR_TITLE"
                          }
                        ]
                      }
                    ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertEquals('failed', $notification->status());
        $this->assertFalse($notification->isMessageRead());
        $this->assertFalse($notification->isMessageDelivered());
        $this->assertFalse($notification->isMessageSent());
        $this->assertTrue($notification->hasErrors());
        $this->assertEquals(131053, $notification->errorCode());
        $this->assertEquals('ERROR_TITLE', $notification->errorTitle());
    }

    public function test_build_from_payload_can_build_a_forwarded_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [{
              "id": "WHATSAPP_BUSINESS_ACCOUNT_ID",
              "changes": [{
                  "value": {
                      "messaging_product": "whatsapp",
                      "metadata": {
                          "display_phone_number": "PHONE_NUMBER",
                          "phone_number_id": "PHONE_NUMBER_ID"
                      },
                      "contacts": [{
                          "profile": {
                            "name": "NAME"
                          },
                          "wa_id": "WHATSAPP_ID"
                        }],
                      "messages": [{
                          "context": {
                            "forwarded": true
                          },
                          "from": "16315551234",
                          "id": "wamid.ID",
                          "timestamp": 1669233778,
                          "type": "text",
                          "text": {
                            "body": "MESSAGE_BODY"
                          }
                        }]
                  },
                  "field": "messages"
                }]
            }]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertNull($notification->replyingToMessageId());
        $this->assertEquals('PHONE_NUMBER_ID', $notification->businessPhoneNumberId());
        $this->assertEquals('PHONE_NUMBER', $notification->businessPhoneNumber());
        $this->assertTrue($notification->isForwarded());
        $this->assertEquals('WHATSAPP_ID', $notification->customer()->id());
        $this->assertEquals('NAME', $notification->customer()->name());
    }

    public function test_build_from_payload_return_null_when_payload_is_empty()
    {
        $notification = $this->notification_factory->buildFromPayload([]);

        $this->assertNull($notification);

        $notification = $this->notification_factory->buildFromPayload(['entry' => []]);

        $this->assertNull($notification);
    }

    public function test_build_from_payload_can_build_an_authentication_status_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "108536708899139",
              "changes": [
                {
                  "value": {
                    "messaging_product": "whatsapp",
                    "metadata": {
                      "display_phone_number": "CUSTOMER_PHONE_NUMBER",
                      "phone_number_id": "CUSTOMER_PHONE_NUMBER"
                    },
                    "statuses": [
                      {
                        "id": "wamid.ID",
                        "status": "delivered",
                        "timestamp": "1685626673",
                        "recipient_id": "CUSTOMER_PHONE_NUMBER",
                        "conversation": {
                          "id": "CONVERSATION_ID",
                          "origin": {
                            "type": "authentication"
                          }
                        },
                        "pricing": {
                          "billable": true,
                          "pricing_model": "CBP",
                          "category": "authentication"
                        }
                      }
                    ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\StatusNotification::class, $notification);
        $this->assertEquals('wamid.ID', $notification->id());
        $this->assertEquals('CUSTOMER_PHONE_NUMBER', $notification->customerId());
        $this->assertEquals('CONVERSATION_ID', $notification->conversationId());
        $this->assertFalse($notification->isBusinessInitiatedConversation());
        $this->assertFalse($notification->isCustomerInitiatedConversation());
        $this->assertFalse($notification->isReferralInitiatedConversation());
        $this->assertEquals('authentication', $notification->conversationType());
        $this->assertEquals('delivered', $notification->status());
        $this->assertFalse($notification->isMessageRead());
        $this->assertTrue($notification->isMessageDelivered());
        $this->assertTrue($notification->isMessageSent());
    }

    public function test_build_from_payload_can_build_a_marketing_status_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "108536708899139",
              "changes": [
                {
                  "value": {
                    "messaging_product": "whatsapp",
                    "metadata": {
                      "display_phone_number": "CUSTOMER_PHONE_NUMBER",
                      "phone_number_id": "CUSTOMER_PHONE_NUMBER"
                    },
                    "statuses": [
                      {
                        "id": "wamid.ID",
                        "status": "delivered",
                        "timestamp": "1685626673",
                        "recipient_id": "CUSTOMER_PHONE_NUMBER",
                        "conversation": {
                          "id": "CONVERSATION_ID",
                          "origin": {
                            "type": "marketing"
                          }
                        },
                        "pricing": {
                          "billable": true,
                          "pricing_model": "CBP",
                          "category": "marketing"
                        }
                      }
                    ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\StatusNotification::class, $notification);
        $this->assertEquals('wamid.ID', $notification->id());
        $this->assertEquals('CUSTOMER_PHONE_NUMBER', $notification->customerId());
        $this->assertEquals('CONVERSATION_ID', $notification->conversationId());
        $this->assertFalse($notification->isBusinessInitiatedConversation());
        $this->assertFalse($notification->isCustomerInitiatedConversation());
        $this->assertFalse($notification->isReferralInitiatedConversation());
        $this->assertEquals('marketing', $notification->conversationType());
        $this->assertEquals('delivered', $notification->status());
        $this->assertFalse($notification->isMessageRead());
        $this->assertTrue($notification->isMessageDelivered());
        $this->assertTrue($notification->isMessageSent());
    }

    public function test_build_from_payload_can_build_an_utility_status_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "108536708899139",
              "changes": [
                {
                  "value": {
                    "messaging_product": "whatsapp",
                    "metadata": {
                      "display_phone_number": "CUSTOMER_PHONE_NUMBER",
                      "phone_number_id": "CUSTOMER_PHONE_NUMBER"
                    },
                    "statuses": [
                      {
                        "id": "wamid.ID",
                        "status": "delivered",
                        "timestamp": "1685626673",
                        "recipient_id": "CUSTOMER_PHONE_NUMBER",
                        "conversation": {
                          "id": "CONVERSATION_ID",
                          "origin": {
                            "type": "utility"
                          }
                        },
                        "pricing": {
                          "billable": true,
                          "pricing_model": "CBP",
                          "category": "utility"
                        }
                      }
                    ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\StatusNotification::class, $notification);
        $this->assertEquals('wamid.ID', $notification->id());
        $this->assertEquals('CUSTOMER_PHONE_NUMBER', $notification->customerId());
        $this->assertEquals('CONVERSATION_ID', $notification->conversationId());
        $this->assertFalse($notification->isBusinessInitiatedConversation());
        $this->assertFalse($notification->isCustomerInitiatedConversation());
        $this->assertFalse($notification->isReferralInitiatedConversation());
        $this->assertEquals('utility', $notification->conversationType());
        $this->assertEquals('delivered', $notification->status());
        $this->assertFalse($notification->isMessageRead());
        $this->assertTrue($notification->isMessageDelivered());
        $this->assertTrue($notification->isMessageSent());
    }

    public function test_build_from_payload_can_build_a_service_status_notification()
    {
        $payload = json_decode('{
          "object": "whatsapp_business_account",
          "entry": [
            {
              "id": "108536708899139",
              "changes": [
                {
                  "value": {
                    "messaging_product": "whatsapp",
                    "metadata": {
                      "display_phone_number": "CUSTOMER_PHONE_NUMBER",
                      "phone_number_id": "CUSTOMER_PHONE_NUMBER"
                    },
                    "statuses": [
                      {
                        "id": "wamid.ID",
                        "status": "delivered",
                        "timestamp": "1685626673",
                        "recipient_id": "CUSTOMER_PHONE_NUMBER",
                        "conversation": {
                          "id": "CONVERSATION_ID",
                          "origin": {
                            "type": "service"
                          }
                        },
                        "pricing": {
                          "billable": true,
                          "pricing_model": "CBP",
                          "category": "service"
                        }
                      }
                    ]
                  },
                  "field": "messages"
                }
              ]
            }
          ]
        }', true);

        $notification = $this->notification_factory->buildFromPayload($payload);

        $this->assertInstanceOf(Notification\StatusNotification::class, $notification);
        $this->assertEquals('wamid.ID', $notification->id());
        $this->assertEquals('CUSTOMER_PHONE_NUMBER', $notification->customerId());
        $this->assertEquals('CONVERSATION_ID', $notification->conversationId());
        $this->assertFalse($notification->isBusinessInitiatedConversation());
        $this->assertFalse($notification->isCustomerInitiatedConversation());
        $this->assertFalse($notification->isReferralInitiatedConversation());
        $this->assertEquals('service', $notification->conversationType());
        $this->assertEquals('delivered', $notification->status());
        $this->assertFalse($notification->isMessageRead());
        $this->assertTrue($notification->isMessageDelivered());
        $this->assertTrue($notification->isMessageSent());
    }
}
