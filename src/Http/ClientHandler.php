<?php

namespace Netflie\WhatsAppCloudApi\Http;

interface ClientHandler
{
    /**
     * Sends a JSON POST request to the server and returns the raw response.
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

    /**
     * Sends a form POST request to the server and returns the raw response.
     *
     * @param string $url     The endpoint to send the request to.
     * @param array  $form    The form data of the request.
     * @param array  $headers The request headers.
     * @param int    $timeout The timeout in seconds for the request.
     *
     * @return RawResponse Response from the server.
     *
     * @throws Netflie\WhatsAppCloudApi\Response\ResponseException
     */
    public function postFormData(string $url, array $form, array $headers, int $timeout): RawResponse;

    /**
     * Sends a GET request to the server and returns the raw response.
     *
     * @param string $url     The endpoint to send the request to.
     * @param array  $headers The request headers.
     * @param int    $timeout The timeout in seconds for the request.
     *
     * @return RawResponse Response from the server.
     *
     * @throws Netflie\WhatsAppCloudApi\Response\ResponseException
     */
    public function get(string $url, array $headers, int $timeout): RawResponse;
}
