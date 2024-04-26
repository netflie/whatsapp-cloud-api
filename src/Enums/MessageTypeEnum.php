<?php
namespace Netflie\WhatsAppCloudApi\Enums;

enum MessageTypeEnum: string
{
    case AUDIO = 'audio';
    case BUTTON = 'button';
    case DOCUMENT = 'document';
    case TEXT = 'text';
    case IMAGE = 'image';
    case INTERACTIVE = 'interactive';
    case ORDER = 'order';
    case STICKER = 'sticker';
    case SYSTEM = 'system';
    case UNKNOWN = 'unknown';
    case VIDEO = 'video';
}

