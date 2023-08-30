<?php

namespace Netflie\WhatsAppCloudApi\Request\BusinessRequest;

use Netflie\WhatsAppCloudApi\Request\BusinessManagmentAccountRequest;

final class RequestBusinessManagementAccount extends BusinessManagmentAccountRequest
{
    /**
     * {@inheritdoc}
     */
    public function body(): array
    {
        return $this->business->data();
    }
}
