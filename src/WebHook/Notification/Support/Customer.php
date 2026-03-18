<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Customer
{
    private ?string $id;

    private string $name;

    private ?string $phone_number;

    private ?string $user_id;

    private ?string $username;

    private ?string $parent_user_id;

    public function __construct(
        ?string $id,
        string $name,
        ?string $phone_number,
        ?string $user_id = null,
        ?string $username = null,
        ?string $parent_user_id = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->phone_number = $phone_number;
        $this->user_id = $user_id;
        $this->username = $username;
        $this->parent_user_id = $parent_user_id;
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function phoneNumber(): ?string
    {
        return $this->phone_number;
    }

    /**
     * Get the Business-Scoped User ID (BSUID).
     */
    public function userId(): ?string
    {
        return $this->user_id;
    }

    /**
     * Get the username (if the user has enabled the username feature).
     */
    public function username(): ?string
    {
        return $this->username;
    }

    /**
     * Get the parent Business-Scoped User ID (for linked portfolios).
     */
    public function parentUserId(): ?string
    {
        return $this->parent_user_id;
    }

    /**
     * Get the best available identifier: phone number or BSUID.
     */
    public function identifier(): string
    {
        return $this->phone_number ?? $this->user_id ?? $this->id ?? '';
    }

    /**
     * Check if this customer has a phone number available.
     */
    public function hasPhoneNumber(): bool
    {
        return !empty($this->phone_number);
    }

    /**
     * Check if this customer has a BSUID available.
     */
    public function hasBsuid(): bool
    {
        return !empty($this->user_id);
    }
}
