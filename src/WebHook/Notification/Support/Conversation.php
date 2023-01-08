<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Conversation
{
    private string $id;

    private ConversationType $type;

    private ?\DateTimeImmutable $expires_at;

    public function __construct(string $id, string $type, ?string $expires_at = null)
    {
        $this->id = $id;
        $this->type = new ConversationType($type);
        $this->expires_at = $expires_at ? (new \DateTimeImmutable())->setTimestamp($expires_at) : null;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): ConversationType
    {
        return $this->type;
    }

    public function expiresAt(): ?\DateTimeImmutable
    {
        return $this->expires_at;
    }

    public function isBusinessInitiated(): bool
    {
        return $this->type->equals(ConversationType::BUSINESS_INITIATED());
    }

    public function isCustomerInitiated(): bool
    {
        return $this->type->equals(ConversationType::CUSTOMER_INITIATED());
    }

    public function isReferralInitiated(): bool
    {
        return $this->type->equals(ConversationType::REFERRAL_INITIATED());
    }
}
