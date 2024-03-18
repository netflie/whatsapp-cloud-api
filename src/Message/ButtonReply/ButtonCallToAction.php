<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

class ButtonCallToAction
{
    /**
     * @var string The title of the CTA
     */
    private $title;

    /**
     * @var string The destination link of the CTA
     */
    private $destination;

    public function __construct(string $title, string $destination)
    {
        $this->title = $title;
        $this->destination = $destination;
    }

    /**
     * Get the title of the CTA
     *
     * @return string The title of the CTA
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get the destination of the CTA
     *
     * @return string The destination url of the CTA
     */
    public function destination(): string
    {
        return $this->destination;
    }
}
