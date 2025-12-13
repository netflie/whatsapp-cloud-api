<?php

namespace Netflie\WhatsAppCloudApi\Message\ButtonReply;

abstract class Header
{
    protected string $type;

    abstract public function getBody(): array;
}