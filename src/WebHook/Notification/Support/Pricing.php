<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification\Support;

final class Pricing
{
    private PricingCategory $category;

    private string $model;

    private bool $billable;

    public function __construct(string $category, string $model, bool $billable)
    {
        $this->category = new PricingCategory($category);
        $this->model = $model;
        $this->billable = $billable;
    }

    public function category(): PricingCategory
    {
        return $this->category;
    }

    public function model(): string
    {
        return $this->model;
    }

    public function isBillable(): bool
    {
        return $this->billable;
    }
}
