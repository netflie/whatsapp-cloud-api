<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Customer
{
    private string $id;

    private CustomerIdType $id_type;

    public function __construct(
        ?string $wa_id,
        private string $name,
        private ?string $phone_number,
        private ?string $username,
        private ?string $user_id,
        private ?string $parent_user_id
    ) {
        $this->id = $wa_id ?? $user_id ?? '';
        $this->id_type = null !== $wa_id ? new CustomerIdType(CustomerIdType::WA_ID) : (null !== $user_id ? new CustomerIdType(CustomerIdType::USER_ID) : new CustomerIdType(CustomerIdType::UNKNOWN));
    }

    public function id(): string
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

    public function username(): ?string
    {
        return $this->username;
    }

    public function userId(): ?string
    {
        return $this->user_id;
    }

    public function parentUserId(): ?string
    {
        return $this->parent_user_id;
    }

    public function idType(): string
    {
        return (string) $this->id_type;
    }
}
