<?php

namespace Netflie\WhatsAppCloudApi\Http;

final class RawResponse
{
    /**
     * @var array The response headers in the form of an associative array.
     */
    private array $headers;

    /**
     * @var string The raw response body.
     */
    private string $body;

    /**
     * @var int The HTTP status response code.
     */
    private $http_response_code;

    /**
     * Creates a new GraphRawResponse entity.
     *
     * @param string|array $headers          The headers as a raw string or array.
     * @param string       $body             The raw response body.
     * @param int          $http_status_code The HTTP response code (if sending headers as parsed array).
     */
    public function __construct($headers, string $body, ?int $http_status_code = null)
    {
        if (is_numeric($http_status_code)) {
            $this->http_response_code = (int)$http_status_code;
        }

        if (is_array($headers)) {
            $this->headers = $headers;
        } else {
            $this->setHeadersFromString($headers);
        }

        $this->body = $body;
    }

    /**
     * Return the response headers.
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Return the body of the response.
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * Return the HTTP response code.
     *
     * @return int
     */
    public function httpResponseCode(): int
    {
        return $this->http_response_code;
    }

    /**
     * Sets the HTTP response code from a raw header.
     *
     * @param string $raw_response_headers
     */
    public function setHttpResponseCodeFromHeader($raw_response_headers)
    {
        // https://tools.ietf.org/html/rfc7230#section-3.1.2
        list($version, $status, $reason) = array_pad(explode(' ', $raw_response_headers, 3), 3, null);
        $this->http_response_code = (int) $status;
    }

    /**
     * Parse the raw headers and set as an array.
     *
     * @param string $raw_headers The raw headers from the response.
     */
    protected function setHeadersFromString($raw_headers)
    {
        // Normalize line breaks
        $raw_headers = str_replace("\r\n", "\n", $raw_headers);

        // There will be multiple headers if a 301 was followed
        // or a proxy was followed, etc
        $header_collection = explode("\n\n", trim($raw_headers));
        // We just want the last response (at the end)
        $raw_header = array_pop($header_collection);

        $header_components = explode("\n", $raw_header);
        foreach ($header_components as $line) {
            if (strpos($line, ': ') === false) {
                $this->setHttpResponseCodeFromHeader($line);
            } else {
                list($key, $value) = explode(': ', $line, 2);
                $this->headers[$key] = $value;
            }
        }
    }
}
