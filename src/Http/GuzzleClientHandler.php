<?php

namespace Netflie\WhatsAppCloudApi\Http;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

final class GuzzleClientHandler implements ClientHandler
{
    /**
     * @var \GuzzleHttp\Client The Guzzle client.
     */
    private $guzzle_client;

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
    public function postJsonData(string $url, array $body, array $headers, int $timeout): RawResponse
    {
        $raw_handler_response = $this->post($url, $body, 'json', $headers, $timeout);

        return $this->buildResponse($raw_handler_response);
    }

    /**
     * {@inheritDoc}
     *
     */
    public function postFormData(string $url, array $form, array $headers, int $timeout): RawResponse
    {
        $raw_handler_response = $this->post($url, $form, 'multipart', $headers, $timeout);

        return $this->buildResponse($raw_handler_response);
    }

    /**
     * {@inheritDoc}
     *
     */
    public function get(string $url, array $headers, int $timeout): RawResponse
    {
        $raw_handler_response = $this->guzzle_client->get($url, [
            'headers' => $headers,
            'timeout' => $timeout,
            'http_errors' => false,
        ]);

        return $this->buildResponse($raw_handler_response);
    }

    protected function post(string $url, array $data, string $data_type, array $headers, int $timeout): ResponseInterface
    {
        return $this->guzzle_client->post($url, [
            $data_type => $data,
            'headers' => $headers,
            'timeout' => $timeout,
            'http_errors' => false,
        ]);
    }

    protected function buildResponse(ResponseInterface $handler_response): RawResponse
    {
        return new RawResponse(
            $handler_response->getHeaders(),
            $handler_response->getBody(),
            $handler_response->getStatusCode()
        );
    }
}
