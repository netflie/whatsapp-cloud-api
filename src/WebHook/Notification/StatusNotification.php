<?php

namespace Netflie\WhatsAppCloudApi\WebHook\Notification;

use Netflie\WhatsAppCloudApi\WebHook\Notification;

final class StatusNotification extends Notification
{
    private ?Support\Conversation $conversation = null;

    private ?Support\Pricing $pricing = null;

    private string $customer_id;

    private Support\Status $status;

    private ?Support\Error $error = null;

    public function __construct(
        string $id,
        Support\Business $business,
        string $customer_id,
        string $status,
        string $received_at
    ) {
        parent::__construct($id, $business, $received_at);

        $this->customer_id = $customer_id;
        $this->status = new Support\Status($status);
    }

    public function withConversation(Support\Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function withPricing(Support\Pricing $pricing): self
    {
        $this->pricing = $pricing;

        return $this;
    }

    public function withError(Support\Error $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function customerId(): string
    {
        return $this->customer_id;
    }

    public function conversationId(): ?string
    {
        if (!$this->conversation) {
            return null;
        }

        return $this->conversation->id();
    }

    public function conversationType(): ?string
    {
        if (!$this->conversation) {
            return null;
        }

        return (string) $this->conversation->type();
    }

    public function conversationExpiresAt(): ?\DateTimeImmutable
    {
        if (!$this->conversation) {
            return null;
        }

        return $this->conversation->expiresAt();
    }

    public function isBusinessInitiatedConversation(): ?bool
    {
        if (!$this->conversation) {
            return null;
        }

        return $this->conversation->isBusinessInitiated();
    }

    public function isCustomerInitiatedConversation(): ?bool
    {
        if (!$this->conversation) {
            return null;
        }

        return $this->conversation->isCustomerInitiated();
    }

    public function isReferralInitiatedConversation(): ?bool
    {
        if (!$this->conversation) {
            return null;
        }

        return $this->conversation->isReferralInitiated();
    }

    public function pricingCategory(): ?string
    {
        if (!$this->pricing) {
            return null;
        }

        return (string) $this->pricing->category();
    }

    public function pricingModel(): ?string
    {
        if (!$this->pricing) {
            return null;
        }

        return (string) $this->pricing->model();
    }

    public function isBillable(): ?bool
    {
        if (!$this->pricing) {
            return null;
        }

        return $this->pricing->isBillable();
    }

    public function status(): string
    {
        return (string) $this->status;
    }

    public function isMessageRead(): bool
    {
        return $this->status->equals(Support\Status::READ());
    }

    public function isMessageDelivered(): bool
    {
        return $this->isMessageRead() || $this->status->equals(Support\Status::DELIVERED());
    }

    public function isMessageSent(): bool
    {
        return $this->isMessageDelivered() || $this->status->equals(Support\Status::SENT());
    }

    public function hasErrors(): bool
    {
        return null !== $this->error;
    }

    public function errorCode(): ?int
    {
        if (!$this->error) {
            return null;
        }

        return $this->error->code();
    }

    public function errorTitle(): ?string
    {
        if (!$this->error) {
            return null;
        }

        return $this->error->title();
    }
}
