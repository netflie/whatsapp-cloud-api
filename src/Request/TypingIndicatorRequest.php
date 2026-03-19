<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Request;

final class TypingIndicatorRequest extends Request implements RequestWithBody
{
    /**
     * @var string WhatsApp Message Id to reference for the typing indicator.
     */
    private string $message_id;

    /**
     * @var string WhatsApp Number Id from which the typing indicator will be sent.
     */
    private string $from_phone_number_id;

    public function __construct(string $message_id, string $access_token, string $from_phone_number_id, ?int $timeout = null)
    {
        $this->message_id = $message_id;
        $this->from_phone_number_id = $from_phone_number_id;

        parent::__construct($access_token, $timeout);
    }

    /**
     * Returns the raw body of the request.
     *
     * @return array
     */
    public function body(): array
    {
        return [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $this->message_id,
            'typing_indicator' => [
                'type' => 'text',
            ],
        ];
    }

    /**
     * Return WhatsApp Number Id for this request.
     *
     * @return string
     */
    public function fromPhoneNumberId(): string
    {
        return $this->from_phone_number_id;
    }

    /**
     * WhatsApp node path.
     *
     * @return string
     */
    public function nodePath(): string
    {
        return $this->from_phone_number_id . '/messages';
    }
}
