<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

/**
 * @method static Status DELIVERED()
 * @method static Status READ()
 * @method static Status SENT()
 * @method static Status FAILED()
 */
final class Status extends \MyCLabs\Enum\Enum
{
    private const DELIVERED = 'delivered';

    private const READ = 'read';

    private const SENT = 'sent';

    private const FAILED = 'failed';
}
