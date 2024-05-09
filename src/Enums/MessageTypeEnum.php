<?php
namespace Netflie\WhatsAppCloudApi\Enums;

enum MessageTypeEnum: string
{
    case AUDIO = 'audio';
    case BUTTON = 'button';
    case CONTACTS = 'contacts';
    case DOCUMENT = 'document';
    case TEXT = 'text';
    case IMAGE = 'image';
    case INTERACTIVE = 'interactive';
    case LOCATION = 'location';
    case ORDER = 'order';
    case REACTION = 'reaction';
    case STICKER = 'sticker';
    case SYSTEM = 'system';
    case TEMPLATE = 'template';
    case UNKNOWN = 'unknown';
    case UNSUPPORTED = 'unsupported';
    case VIDEO = 'video';
    case VOICE = 'voice';

    public static function isMedia($type): bool
    {
        $mediaTypes = [
            MessageTypeEnum::AUDIO,
            MessageTypeEnum::DOCUMENT,
            MessageTypeEnum::IMAGE,
            MessageTypeEnum::STICKER,
            MessageTypeEnum::VIDEO,
            MessageTypeEnum::VOICE,
        ];

        return in_array($type, $mediaTypes);
    }
}
