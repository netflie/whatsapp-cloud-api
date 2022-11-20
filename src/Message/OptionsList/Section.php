<?php

namespace Netflie\WhatsAppCloudApi\Message\OptionsList;

class Section
{
    protected string $title;

    /** @var Row[] */
    protected array $rows;

    public function __construct(string $title, array $rows)
    {
        $this->title = $title;
        $this->rows = $rows;
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return Row[]
     */
    public function rows(): array
    {
        $result = [];

        foreach ($this->rows as $row) {
            $result[] = [
                'id' => $row->id(),
                'title' => $row->title(),
                'description' => $row->description() ?: null,
            ];
        }

        return $result;
    }
}
