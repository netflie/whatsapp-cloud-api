<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

class ButtonAction
{
    private $buttons;

    public function __construct(array $buttons)
    {
        $this->buttons = $buttons;
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
}
