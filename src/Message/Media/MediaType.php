<?php

declare(strict_types=1);

namespace Netflie\WhatsAppCloudApi\Message\Media;

use MyCLabs\Enum\Enum;

/**
 * @method static MediaType STICKER()
 * @method static MediaType IMAGE()
 * @method static MediaType DOCUMENT()
 * @method static MediaType AUDIO()
 * @method static MediaType VIDEO()
 * @method static MediaType VOICE()
 */
final class MediaType extends Enum
{
    private const STICKER = 'sticker';

    private const IMAGE = 'image';

    private const DOCUMENT = 'document';

    private const AUDIO = 'audio';

    private const VIDEO = 'video';

    private const VOICE = 'voice';
}
