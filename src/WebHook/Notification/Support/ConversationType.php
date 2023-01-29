<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

/**
 * @method static ConversationType BUSINESS_INITIATED()
 * @method static ConversationType CUSTOMER_INITIATED()
 * @method static ConversationType REFERRAL_INITIATED()
 */
final class ConversationType extends \MyCLabs\Enum\Enum
{
    private const BUSINESS_INITIATED = 'business_initiated';

    private const CUSTOMER_INITIATED = 'customer_initiated';

    private const REFERRAL_INITIATED = 'referral_conversion';
}
