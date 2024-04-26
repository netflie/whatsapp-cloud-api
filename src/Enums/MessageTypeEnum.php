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
    case STICKER = 'sticker';
    case SYSTEM = 'system';
    case UNKNOWN = 'unknown';
    case VIDEO = 'video';
    case VOICE = 'voice';

    public static function isMedia(string $type): bool
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

