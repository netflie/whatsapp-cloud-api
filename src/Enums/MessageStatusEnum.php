<?php
namespace Netflie\WhatsAppCloudApi\Enums;

enum MessageStatusEnum: string
{
    case DELIVERED = "delivered";
    case READ = "read";
    case SENT = "sent";
    case FAILED = "failed";
}
