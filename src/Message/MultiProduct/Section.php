<?php

namespace Netflie\WhatsAppCloudApi\Message\MultiProduct;

final class Section
{
    protected string $title;

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

    public function rows(): array
    {
        $result = [];

        foreach ($this->rows as $row) {
            $result[] = [
                'product_retailer_id' => $row->product_retailer_id(),
            ];
        }

        return $result;
    }
}
