<?php

namespace Netflie\WhatsAppCloudApi\Message\MultiProduct;

final class Action
{
    protected array $sections;

    public function __construct(array $sections)
    {
        $this->sections = $sections;
    }

    public function sections(): array
    {
        $result = [];

        foreach ($this->sections as $section) {
            $result[] = [
                'title' => $section->title(),
                'product_items' => $section->rows(),
            ];
        }

        return $result;
    }
}
