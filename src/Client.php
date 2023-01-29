<?php

namespace Netflie\WhatsAppCloudApi;

use Netflie\WhatsAppCloudApi\Http\ClientHandler;
use Netflie\WhatsAppCloudApi\Http\GuzzleClientHandler;

class Client
{
    /**
     * @const string Production Graph API URL.
     */
    public const BASE_GRAPH_URL = 'https://graph.facebook.com';

    /**
     * @var ClientHandler The HTTP client handler to send the request.
     */
    protected ClientHandler $handler;

    /**
     * @var string Graph API version used.
     */
    protected string $graph_version;

    /**
     * Creates a new HTTP Client.
     *
     * @param string                    $graph_version
     * @param ClientHandler|null        $handler
     */
    public function __construct(string $graph_version, ?ClientHandler $handler = null)
    {
        $this->handler = $handler ?? $this->defaultHandler();

        $this->graph_version = $graph_version;
    }

    /**
     * Send a message request to.
     *
     * @return Response Raw response from the server.
     *
     * @throws Netflie\WhatsAppCloudApi\Response\ResponseException
     */
    public function sendMessage(Request\RequestWithBody $request): Response
    {
        $raw_response = $this->handler->postJsonData(
            $this->buildRequestUri($request->nodePath()),
            $request->body(),
            $request->headers(),
            $request->timeout()
        );

        $return_response = new Response(
            $request,
            $raw_response->body(),
            $raw_response->httpResponseCode(),
            $raw_response->headers()
        );

        if ($return_response->isError()) {
            $return_response->throwException();
        }

        return $return_response;
    }

    /**
     * Upload a media file to Facebook servers.
     *
     * @return Response Raw response from the server.
     *
     * @throws Netflie\WhatsAppCloudApi\Response\ResponseException
     */
    public function uploadMedia(Request\MediaRequest\UploadMediaRequest $request): Response
    {
        $raw_response = $this->handler->postFormData(
            $this->buildRequestUri($request->nodePath()),
            $request->form(),
            $request->headers(),
            $request->timeout()
        );

        $return_response = new Response(
            $request,
            $raw_response->body(),
            $raw_response->httpResponseCode(),
            $raw_response->headers()
        );

        if ($return_response->isError()) {
            $return_response->throwException();
        }

        return $return_response;
    }

    /**
     * Download a media file from Facebook servers.
     *
     * @return Response Raw response from the server.
     *
     * @throws Netflie\WhatsAppCloudApi\Response\ResponseException
     */
    public function downloadMedia(Request\MediaRequest\DownloadMediaRequest $request): Response
    {
        $raw_response = $this->handler->get(
            $this->buildRequestUri($request->nodePath()),
            $request->headers(),
            $request->timeout()
        );

        $response = Response::fromClientResponse($request, $raw_response);
        $media_url = $response->decodedBody()['url'];

        $raw_response = $this->handler->get(
            $media_url,
            $request->headers(),
            $request->timeout()
        );

        $return_response = Response::fromClientResponse($request, $raw_response);

        if ($return_response->isError()) {
            $return_response->throwException();
        }

        return $return_response;
    }

    private function defaultHandler(): ClientHandler
    {
        return new GuzzleClientHandler();
    }

    private function buildBaseUri(): string
    {
        return self::BASE_GRAPH_URL . '/' . $this->graph_version;
    }

    private function buildRequestUri(string $node_path): string
    {
        return $this->buildBaseUri() . '/' . $node_path;
    }
}
