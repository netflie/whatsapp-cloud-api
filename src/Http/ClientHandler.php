<?php

namespace Netflie\WhatsAppCloudApi\Http;

interface ClientHandler
{
    /**
     * Sends a POST request to the server and returns the raw response.
     *
     * @param string $url     The endpoint to send the request to.
     * @param array  $body    The body of the request.
     * @param array  $headers The request headers.
     * @param int    $timeout The timeout in seconds for the request.
     *
     * @return RawResponse Response from the server.
     *
     * @throws Netflie\WhatsAppCloudApi\Response\ResponseException
     */
    public function postJsonData(string $url, array $body, array $headers, int $timeout): RawResponse;
}
