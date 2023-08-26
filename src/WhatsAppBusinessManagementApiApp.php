<?php

namespace Netflie\WhatsAppCloudApi;

use Dotenv\Dotenv;

class WhatsAppBusinessManagementApiApp
{
    /**
     * @const string The name of the environment variable that contains the app access token.
     */
    public const APP_TOKEN_ENV_NAME = 'WHATSAPP_CLOUD_API_TOKEN';

    /**
     * @const string The name of the environment variable that contains the app whatsapp business account ID.
     */
    public const APP_WHATSAPP_BUSINESS_ACCOUNT_ID_ENV_NAME = 'WHATSAPP_BUSINESS_ACCOUNT_ID';

    /**
     * @const string Facebook Whatsapp Access Token.
     */
    protected string $access_token;

    /**
     * @const string Facebook Whatsapp Business Account ID.
     */
    protected string $whatsapp_business_account_id;

    /**
     * Sends a Whatsapp text message.
     *
     * @param string|null $whatsapp_business_account_id The Facebook Whatsapp Business Account ID.
     * @param string|null $access_token The Facebook Whatsapp Access Token.
     */
    public function __construct(?string $whatsapp_business_account_id = null, ?string $access_token = null)
    {
        $this->loadEnv();

        $this->whatsapp_business_account_id = $whatsapp_business_account_id ?: $_ENV[static::APP_WHATSAPP_BUSINESS_ACCOUNT_ID_ENV_NAME] ?? null;
        $this->access_token = $access_token ?: $_ENV[static::APP_TOKEN_ENV_NAME] ?? null;

        $this->validate($this->whatsapp_business_account_id, $this->access_token);
    }

    /**
     * Returns the Facebook Whatsapp Access Token.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->access_token;
    }

    /**
     * Returns the Facebook Whatsapp Business Account ID.
     *
     * @return string
     */
    public function whatsappBusinessAccountId(): string
    {
        return $this->whatsapp_business_account_id;
    }

    private function validate(string $whatsapp_business_account_id, string $access_token): void
    {
        // validate by function type hinting
    }

    private function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();
    }
}
