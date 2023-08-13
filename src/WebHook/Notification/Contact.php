<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

final class Contact extends MessageNotification
{
    private array $addresses;

    private array $emails;

    private array $name;

    private array $company;

    private array $phones;

    private array $urls;

    private ?\DateTimeImmutable $birthday;

    public function __construct(
        string $id,
        Support\Business $business,
        array $addresses,
        array $emails,
        array $name,
        array $company,
        array $phones,
        array $urls,
        ?string $birthday,
        string $received_at_timestamp
    ) {
        parent::__construct($id, $business, $received_at_timestamp);

        $this->name = $name;
        $this->addresses = $addresses;
        $this->emails = $emails;
        $this->company = $company;
        $this->phones = $phones;
        $this->urls = $urls;
        $this->birthday = $birthday ? \DateTimeImmutable::createFromFormat('Y-m-d', $birthday) : null;
    }

    public function name(): array
    {
        return $this->name;
    }

    public function formattedName(): string
    {
        return $this->name['formatted_name'];
    }

    public function firstName(): string
    {
        return $this->name['first_name'];
    }

    public function lastName(): string
    {
        return $this->name['last_name'] ?? '';
    }

    public function middleName(): string
    {
        return $this->name['middle_name'] ?? '';
    }

    public function addresses(): array
    {
        return $this->addresses;
    }

    public function birthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function emails(): array
    {
        return $this->emails;
    }

    public function company(): array
    {
        return $this->company;
    }

    public function companyName(): string
    {
        return $this->company['company'] ?? '';
    }

    public function companyDepartment(): string
    {
        return $this->company['department'] ?? '';
    }

    public function companyTitle(): string
    {
        return $this->company['title'] ?? '';
    }

    public function phones(): array
    {
        return $this->phones;
    }

    public function urls(): array
    {
        return $this->urls;
    }
}
