<?php

namespace Netflie\WhatsAppCloudApi\Message\Button;

class Action
{
    /** @var Button[] */
    protected array $buttons;

    public function __construct(array $buttons)
    {
        $this->buttons = $buttons;
    }

    /**
     * @return Button[]
     */
    public function buttons(): array
    {
        $result = [];

        foreach ($this->buttons as $button) {
            $result[] = [
                'type' => $button->type(),
                'reply' => [
                    'id' => $button->id(),
                    'title' => $button->title(),
                ]
            ];
        }

        return $result;
    }
}
