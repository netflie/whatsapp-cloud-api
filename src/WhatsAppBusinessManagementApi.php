<?php

namespace Netflie\WhatsAppCloudApi;

class WhatsAppBusinessManagementApi
{
    /**
     * @const string Default Graph API version.
     */
    public const DEFAULT_GRAPH_VERSION = 'v15.0';

    /**
     * @var WhatsAppBusinessManagementApiApp The WhatsApp Business Management Api app.
     */
    protected WhatsAppBusinessManagementApiApp $app;

    /**
     * @var Client The WhatsApp Business Management Api client service.
     */
    protected Client $client;

    /**
     * @var int|null The WhatsApp Business Management Api client timeout.
     */
    protected ?int $timeout;

    /**
     * Instantiates a new WhatsAppCloudApi super-class object.
     *
     * @param array $config WhatsAppCloudApi configuration. [whatsapp_business_account_id, access_token, graph_version, client_handler, timeout]
     *
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'whatsapp_business_account_id' => null,
            'access_token' => null,
            'graph_version' => static::DEFAULT_GRAPH_VERSION,
            'client_handler' => null,
            'timeout' => null,
        ], $config);

        $this->app = new WhatsAppBusinessManagementApiApp($config['whatsapp_business_account_id'], $config['access_token']);
        $this->timeout = $config['timeout'];
        $this->client = new Client($config['graph_version'], $config['client_handler']);
    }

    /**
     * Get a business account details.
     *
     * @param string $path
     * @return Response
     */
    public function getBusinessManagementAccount(string $path = ''): Response
    {
        $business = new Business\BusinessManagementAccount($path);
        $request = new Request\BusinessRequest\RequestBusinessManagementAccount(
            $business,
            $this->app->accessToken(),
            $this->app->whatsappBusinessAccountId(),
            $this->timeout
        );

        return $this->client->get($request);
    }

    /**
     * Create business account details.
     *
     * @param string $path
     * @param array $data
     * @return Response
     */
    public function postBusinessManagementAccount(string $path = '', array $data = []): Response
    {
        $business = new Business\BusinessManagementAccount($path, $data);
        $request = new Request\BusinessRequest\RequestBusinessManagementAccount(
            $business,
            $this->app->accessToken(),
            $this->app->whatsappBusinessAccountId(),
            $this->timeout
        );

        return $this->client->post($request);
    }
}
