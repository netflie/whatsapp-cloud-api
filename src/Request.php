<?php

namespace Netflie\WhatsAppCloudApi;

use Netflie\WhatsAppCloudApi\Message\Message;

abstract class Request
{
    /**
     * @const int The timeout in seconds for a normal request.
     */
    public const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var Message WhatsApp Message to be sent.
     */
    protected Message $message;

    /**
     * @var string The access token to use for this request.
     */
    protected string $access_token;

    /**
     * @var string WhatsApp Number Id from messages will sent.
     */
    protected string $from_phone_number_id;

    /**
     * The raw body request.
     *
     * @return array
     */
    protected array $body;

    /**
     * The raw encoded body request.
     *
     * @return string
     */
    protected string $encoded_body;

    /**
     * The timeout request.
     *
     * @return int
     */
    protected int $timeout;

    /**
     * Creates a new Request entity.
     *
     * @param Message               $message
     * @param string                $access_token
     */
    public function __construct(Message $message, string $access_token, string $from_phone_number_id, ?int $timeout = null)
    {
        $this->message = $message;
        $this->access_token = $access_token;
        $this->from_phone_number_id = $from_phone_number_id;
        $this->timeout = $timeout ?? static::DEFAULT_REQUEST_TIMEOUT;

        $this->makeBody();
        $this->encodeBody();
    }

    /**
     * Returns the raw body of the request.
     *
     * @return array
     */
    public function body(): array
    {
        return $this->body;
    }

    /**
     * Returns the body of the request encoded.
     *
     * @return string
     */
    public function encodedBody(): string
    {
        return $this->encoded_body;
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
            'Content-Type' => 'application/json',
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
     * Return WhatsApp Number Id for this request.
     *
     * @return string
     */
    public function fromPhoneNumberId(): string
    {
        return $this->from_phone_number_id;
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

    /**
     * Makes the raw body of the request.
     *
     * @return array
     */
    abstract protected function makeBody(): void;

    /**
     * Encodes the raw body of the request.
     *
     * @return array
     */
    private function encodeBody(): void
    {
        $this->encoded_body = json_encode($this->body());
    }
}
