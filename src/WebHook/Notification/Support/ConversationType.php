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

    private const CUSTOMER_INITIATED = 'user_initiated';

    private const REFERRAL_INITIATED = 'referral_conversion';

    private const AUTHENTICATION = 'authentication';

    private const MARKETING = 'marketing';

    private const UTILITY = 'utility';

    private const SERVICE = 'service';
}
