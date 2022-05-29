<?php

namespace Netflie\WhatsAppCloudApi;

use Netflie\WhatsAppCloudApi\Message\DocumentMessage;
use Netflie\WhatsAppCloudApi\Message\Document\Document;
use Netflie\WhatsAppCloudApi\Message\Media\MediaID;
use Netflie\WhatsAppCloudApi\Message\TemplateMessage;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\Message\TextMessage;
use Netflie\WhatsAppCloudApi\Request\RequestDocumentMessage;
use Netflie\WhatsAppCloudApi\Request\RequestTemplateMessage;
use Netflie\WhatsAppCloudApi\Request\RequestTextMessage;

class WhatsAppCloudApi
{
    /**
     * @const string Default Graph API version.
     */
    public const DEFAULT_GRAPH_VERSION = 'v13.0';

    /**
     * @var WhatsAppCloudApiApp The WhatsAppCloudApiApp entity.
     */
    protected WhatsAppCloudApiApp $app;

    /**
     * @var Client The WhatsApp Cloud Api client service.
     */
    protected Client $client;

    /**
     * @var int The WhatsApp Cloud Api client timeout.
     */
    protected ?int $timeout;

    /**
     * Instantiates a new WhatsAppCloudApi super-class object.
     *
     * @param array $config
     *
     */
    public function __construct(array $config)
    {
        $config = array_merge([
            'from_phone_number_id' => null,
            'access_token' => '',
            'graph_version' => static::DEFAULT_GRAPH_VERSION,
            'client_handler' => null,
            'timeout' => null,
        ], $config);

        $this->app = new WhatsAppCloudApiApp($config['from_phone_number_id'], $config['access_token']);
        $this->timeout = $config['timeout'];
        $this->client = new Client($config['graph_version'], $config['client_handler']);
    }

    /**
     * Sends a Whatsapp text message.
     *
     * @param string WhatsApp ID or phone number for the person you want to send a message to.
     * @param string The body of the text message.
     * @param bool Determines if show a preview box for URLs contained in the text message.
     *
     * @throws Response\ResponseException
     */
    public function sendTextMessage(string $to, string $text, bool $preview_url = false): Response
    {
        $message = new TextMessage($to, $text, $preview_url);
        $request = new RequestTextMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a document uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some document uploaded on Internet.
     *
     * @param  string   $to         WhatsApp ID or phone number for the person you want to send a message to.
     * @param  Document $document   Document to send. See documents accepted in the Message/Document folder.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendDocument(string $to, MediaID $document_id, string $name, ?string $caption): Response
    {
        $message = new DocumentMessage($to, $document_id, $name, $caption);
        $request = new RequestDocumentMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Sends a message template.
     *
     * @param  string         $to              WhatsApp ID or phone number for the person you want to send a message to.
     * @param  string         $template_name   Name of the template to send.
     * @param  string         $language        Language code
     * @param  Component|null $component       Component parameters of a template
     *
     * @link https://developers.facebook.com/docs/whatsapp/api/messages/message-templates#supported-languages See language codes supported.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendTemplate(string $to, string $template_name, string $language = 'en_US', ?Component $components = null): Response
    {
        $message = new TemplateMessage($to, $template_name, $language, $components);
        $request = new RequestTemplateMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendRequest($request);
    }

    /**
     * Returns the Facebook Whatsapp Access Token.
     *
     * @return string
     */
    public function accessToken(): string
    {
        return $this->app->accessToken();
    }

    /**
     * Returns the Facebook Phone Number ID.
     *
     * @return string
     */
    public function fromPhoneNumberId(): string
    {
        return $this->app->fromPhoneNumberId();
    }
}
