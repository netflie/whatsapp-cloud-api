<?php

namespace Netflie\WhatsAppCloudApi\Business;

abstract class BusinessManagement
{
    /**
     * Path to be returned in the response.
     * @var string path
     */
    private string $path;

    /**
     * Creates a new Business class.
     *
     * @param string $path
     */
    public function __construct(string $path = '')
    {
        $this->path = $path;
    }

    /**
     * Return the set path.
     *
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }
}
