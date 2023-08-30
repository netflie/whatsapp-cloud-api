<?php

namespace Netflie\WhatsAppCloudApi\Business;

final class BusinessManagementAccount extends BusinessManagement
{
    /**
     * Creates a new Business Management Account class.
     *
     * @param string $path
     * @param array $data
     */
    public function __construct(string $path = '', array $data = [])
    {
        parent::__construct($path, $data);
    }
}
