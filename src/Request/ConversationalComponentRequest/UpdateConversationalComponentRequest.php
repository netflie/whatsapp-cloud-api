<?php

namespace Netflie\WhatsAppCloudApi\Request\ConversationalComponentRequest;

use Netflie\WhatsAppCloudApi\Request;

final class UpdateConversationalComponentRequest extends Request
{
    private string $from_phone_number_id;
    private array $components;

    public function __construct(array $components, string $access_token, string $from_phone_number_id, ?int $timeout = null)
    {
        $this->from_phone_number_id = $from_phone_number_id;
        $this->components = $components;
        parent::__construct($access_token, $timeout);
    }

    public function body(): array
    {
        return array_merge(
            [
                'messaging_product' => 'whatsapp',
            ],
            $this->components
        );
    }

    public function nodePath(): string
    {
        return $this->from_phone_number_id . '/conversational_automation';
    }
}
