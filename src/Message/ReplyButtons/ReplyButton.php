<?php


namespace Netflie\WhatsAppCloudApi\Message\ReplyButtons;


class ReplyButton
{
    protected string $id;

    protected string $title;

    public function __construct(string $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }


    public function id():string
    {
        return $this->id;
    }

    public function title():string
    {
        return $this->title;
    }

    public function button(): array
    {
        return [
            'type' => 'reply',
            'reply' => [
                'id' => $this->id,
                'title' => $this->title
            ]
        ];
    }
}
