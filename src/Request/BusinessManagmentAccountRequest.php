<?php

namespace Netflie\WhatsAppCloudApi\Request;

use Netflie\WhatsAppCloudApi\Business\BusinessManagement;
use Netflie\WhatsAppCloudApi\Request;

abstract class BusinessManagmentAccountRequest extends Request implements RequestWithBody
{
    /**
     * @var BusinessManagement
     */
    protected BusinessManagement $business;

    /**
     * @var string WhatsApp Business account ID.
     */
    private string $whatsapp_business_account_id;

    public function __construct(BusinessManagement $business, string $access_token, string $whatsapp_business_account_id, ?int $timeout = null)
    {
        $this->business = $business;
        $this->whatsapp_business_account_id = $whatsapp_business_account_id;

        parent::__construct($access_token, $timeout);
    }

    /**
     * @return string Return Whatsapp Business Account ID for this request.
     */
    public function whatsappBusinessAccountId(): string
    {
        return $this->whatsapp_business_account_id;
    }

    /**
     * @return string WhatsApp Business API node path for this request.
     */
    public function nodePath(): string
    {
        return $this->whatsappBusinessAccountId() . $this->business->path();
    }
}
