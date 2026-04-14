<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class CustomerIdType implements \Stringable
{
    public const WA_ID = 'wa_id';
    public const USER_ID = 'user_id';
    public const UNKNOWN = 'unknown';

    public function __construct(
        private string $type
    ) {
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
