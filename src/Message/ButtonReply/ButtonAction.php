<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonHeader;

class ButtonAction
{
    private $buttons;
    /**
     * @var ButtonHeader|null The header of the button action, can be null
     */
    private ?ButtonHeader $header;

    public function __construct(array $buttons, ?ButtonHeader $header = null)
    {
        $this->buttons = $buttons;
        $this->header = $header;
    }

    public function buttons(): array
    {
        $buttonActions = [];

        foreach ($this->buttons as $button) {
            $buttonActions[] = [
                "type" => "reply",
                "reply" => [
                    "id" => $button->id(),
                    "title" => $button->title(),
                ],
            ];
        }

        return $buttonActions;
    }

    /**
     * Get the header of the button action, if it exists
     *
     * @return array|null The header of the button action, or null if it doesn't exist
     */
    public function header(): array
    {
        $header = [];

        if (!$this->header) {
            return $header;
        }

        $header[] = [
            "type" => $this->header->type(),
            $this->header->type() => $this->header->payload()
        ];

        return $header[0];
    }
}
