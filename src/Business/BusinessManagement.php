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
     * Data to be sent in the request
     * @var array data
     */
    private array $data;

    /**
     * Creates a new Business class.
     *
     * @param string $path
     * @param array $data
     */
    public function __construct(string $path = '', array $data = [])
    {
        $this->path = $path;
        $this->data = $data;
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

    /**
     * Return the set data.
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }
}
