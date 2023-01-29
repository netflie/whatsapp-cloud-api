<?php

namespace Netflie\WhatsAppCloudApi\Message\Contact;

use MyCLabs\Enum\Enum;

final class PhoneType extends Enum
{
    private const CELL = 'cell';
    private const MAIN = 'main';
    private const IPHONE = 'iphone';
    private const HOME = 'home';
    private const WORK = 'work';
}
