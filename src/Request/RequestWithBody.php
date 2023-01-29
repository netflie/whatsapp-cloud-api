<?php

namespace Netflie\WhatsAppCloudApi\Request;

interface RequestWithBody
{
    /**
     * Returns the raw body of the request.
     *
     * @return array
     */
    public function body(): array;
}
