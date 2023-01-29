<?php

namespace Netflie\WhatsAppCloudApi;

abstract class Request
{
    /**
     * @const int The timeout in seconds for a normal request.
     */
    public const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var string The access token to use for this request.
     */
    private string $access_token;

    /**
     * The timeout request.
     *
     * @return int
     */
    private int $timeout;

    /**
     * Creates a new Request entity.
     *
     * @param Message               $message
     * @param string                $access_token
     */
    public function __construct(string $access_token, ?int $timeout = null)
    {
        $this->access_token = $access_token;
        $this->timeout = $timeout ?? static::DEFAULT_REQUEST_TIMEOUT;
    }

    /**
     * Return the headers for this request.
     *
     * @return array
     */
    public function headers(): array
    {
        return [
            'Authorization' => "Bearer $this->access_token",
        ];
    }

    /**
     * Return the access token for this request.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->access_token;
    }

    /**
     * Return the timeout for this request.
     *
     * @return int
     */
    public function timeout(): int
    {
        return $this->timeout;
    }
}
