<?php

namespace Netflie\WhatsAppCloudApi\Business;

final class BusinessManagementAccount extends BusinessManagement
{
    /**
     * Creates a new Business Management Account class.
     *
     * @param string $path
     */
    public function __construct(string $path = '')
    {
        parent::__construct($path);
    }
}
