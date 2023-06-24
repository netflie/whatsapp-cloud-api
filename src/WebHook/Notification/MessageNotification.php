<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification;

abstract class MessageNotification extends Notification
{
    protected ?Support\Context $context = null;

    protected ?Support\Customer $customer = null;

    protected ?Support\Referral $referral = null;

    public function customer(): ?Support\Customer
    {
        return $this->customer;
    }

    public function replyingToMessageId(): ?string
    {
        if (!$this->context) {
            return null;
        }

        return $this->context->replyingToMessageId();
    }

    public function isForwarded(): bool
    {
        if (!$this->context) {
            return false;
        }

        return $this->context->isForwarded();
    }

    public function context(): ?Support\Context
    {
        return $this->context;
    }

    public function referral(): ?Support\Referral
    {
        return $this->referral;
    }

    public function withContext(Support\Context $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function withReferral(Support\Referral $referral): self
    {
        $this->referral = $referral;

        return $this;
    }

    public function withCustomer(Support\Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
