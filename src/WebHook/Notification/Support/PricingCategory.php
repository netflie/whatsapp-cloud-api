<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

/**
 * @method static PricingCategory AUTHENTICATION()
 * @method static PricingCategory MARKETING()
 * @method static PricingCategory UTILITY()
 * @method static PricingCategory SERVICE()
 * @method static PricingCategory REFERRAL_INITIATED()
 */
final class PricingCategory extends \MyCLabs\Enum\Enum
{
    private const AUTHENTICATION = 'authentication';

    private const MARKETING = 'marketing';

    private const UTILITY = 'utility';

    private const SERVICE = 'service';

    private const REFERRAL_INITIATED = 'referral_conversion';
}
