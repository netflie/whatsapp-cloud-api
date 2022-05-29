<?php

namespace Netflie\WhatsAppCloudApi\Response;

use Netflie\WhatsAppCloudApi\Message\Response;

class ResponseException extends \Exception
{
    /**
     * @var Response The response that threw the exception.
     */
    protected $response;

    /**
     * @var array Decoded response.
     */
    protected $response_data;

    /**
     * Creates a ResponseException.
     *
     * @param Response      $response          The response that threw the exception.
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->response_data = $response->decodedBody();
    }

    /**
     * Returns the HTTP status code
     *
     * @return int
     */
    public function httpStatusCode()
    {
        return $this->response->httpStatusCode();
    }

    /**
     * Returns the raw response used to create the exception.
     *
     * @return string
     */
    public function rawResponse()
    {
        return $this->response->body();
    }

    /**
     * Returns the decoded response used to create the exception.
     *
     * @return array
     */
    public function responseData()
    {
        return $this->response_data;
    }

    /**
     * Returns the response entity used to create the exception.
     *
     * @return Response
     */
    public function response()
    {
        return $this->response;
    }
}
