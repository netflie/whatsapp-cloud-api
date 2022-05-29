<?php

namespace Netflie\WhatsAppCloudApi\Http;

use GuzzleHttp\Client;

class GuzzleClientHandler implements ClientHandler
{
    /**
     * @var \GuzzleHttp\Client The Guzzle client.
     */
    protected $guzzle_client;

    /**
     * @param \GuzzleHttp\Client|null The Guzzle client.
     */
    public function __construct(?Client $guzzle_client = null)
    {
        $this->guzzle_client = $guzzle_client ?: new Client();
    }

    /**
     * {@inheritDoc}
     *
     */
    public function send(string $url, string $body, array $headers, int $timeout): RawResponse
    {
        $raw_handler_response = $this->guzzle_client->post($url, [
            'body' => $body,
            'headers' => $headers,
            'timeout' => $timeout,
        ]);

        return new RawResponse(
            $raw_handler_response->getHeaders(),
            $raw_handler_response->getBody(),
            $raw_handler_response->getStatusCode()
        );
    }
}
