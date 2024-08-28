<?php

namespace Netflie\WhatsAppCloudApi;

use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Message\Contact\ContactName;
use Netflie\WhatsAppCloudApi\Message\Contact\Phone;
use Netflie\WhatsAppCloudApi\Message\CtaUrl\Header;
use Netflie\WhatsAppCloudApi\Message\Media\MediaID;
use Netflie\WhatsAppCloudApi\Message\MultiProduct\Action as MultiProductAction;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\Template\Component;

class WhatsAppCloudApi
{
    /**
     * @const string Default Graph API version.
     */
    public const DEFAULT_GRAPH_VERSION = 'v18.0';

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
     * The WhatsApp Message ID to reply to.
     */
    private ?string $reply_to = null;

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
            'business_id' => '',
            'graph_version' => static::DEFAULT_GRAPH_VERSION,
            'client_handler' => null,
            'timeout' => null,
        ], $config);

        $this->app = new WhatsAppCloudApiApp(
            $config['from_phone_number_id'],
            $config['access_token'],
            $config['business_id']
        );
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
        $message = new Message\TextMessage($to, $text, $preview_url, $this->reply_to);
        $request = new Request\MessageRequest\RequestTextMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a document uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some document uploaded on Internet.
     *
     * @param  string   $to         WhatsApp ID or phone number for the person you want to send a message to.
     * @param  MediaID $document_id WhatsApp Media ID or any Internet public document link.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendDocument(string $to, MediaID $document_id, string $name, ?string $caption = ''): Response
    {
        $message = new Message\DocumentMessage($to, $document_id, $name, $caption, $this->reply_to);
        $request = new Request\MessageRequest\RequestDocumentMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
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
        $message = new Message\TemplateMessage($to, $template_name, $language, $components, $this->reply_to);
        $request = new Request\MessageRequest\RequestTemplateMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends an audio uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some audio uploaded on Internet.
     *
     * @param  string  $to         WhatsApp ID or phone number for the person you want to send a message to.
     * @param  MediaId $audio_id   WhatsApp Media ID or any Internet public audio link.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendAudio(string $to, MediaID $audio_id): Response
    {
        $message = new Message\AudioMessage($to, $audio_id, $this->reply_to);
        $request = new Request\MessageRequest\RequestAudioMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends an image uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some image uploaded on Internet.
     *
     * @param  string   $to          WhatsApp ID or phone number for the person you want to send a message to.
     * @param  string   $caption     Description of the specified image file.
     * @param  MediaId  $image_id    WhatsApp Media ID or any Internet public image link.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendImage(string $to, MediaID $image_id, ?string $caption = ''): Response
    {
        $message = new Message\ImageMessage($to, $image_id, $caption, $this->reply_to);
        $request = new Request\MessageRequest\RequestImageMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a video uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some video uploaded on Internet.
     *
     * @param  string   $to       WhatsApp ID or phone number for the person you want to send a message to.
     * @param  MediaId  $video_id WhatsApp Media ID or any Internet public video link.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendVideo(string $to, MediaID $video_id, string $caption = ''): Response
    {
        $message = new Message\VideoMessage($to, $video_id, $caption, $this->reply_to);
        $request = new Request\MessageRequest\RequestVideoMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a sticker uploaded to the WhatsApp Cloud servers by it Media ID or you also
     * can put any public URL of some sticker uploaded on Internet.
     *
     * @param  string   $to             WhatsApp ID or phone number for the person you want to send a message to.
     * @param  MediaId  $sticker_id    WhatsApp Media ID or any Internet public sticker link.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendSticker(string $to, MediaID $sticker_id): Response
    {
        $message = new Message\StickerMessage($to, $sticker_id, $this->reply_to);
        $request = new Request\MessageRequest\RequestStickerMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a location
     *
     * @param  string   $to         WhatsApp ID or phone number for the person you want to send a message to.
     * @param  float    $longitude  Longitude position.
     * @param  float    $latitude   Latitude position.
     * @param  string   $name       Name of location sent.
     * @param  address  $address    Address of location sent.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendLocation(string $to, float $longitude, float $latitude, string $name = '', string $address = ''): Response
    {
        $message = new Message\LocationMessage($to, $longitude, $latitude, $name, $address, $this->reply_to);
        $request = new Request\MessageRequest\RequestLocationMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a location request message.
     *
     * @param string $to   The WhatsApp ID or phone number for the person you want to send the message to.
     * @param string $body The body of the location request message.
     *
     * @return Response The response object containing the result of the API request.
     *
     * @throws Response\ResponseException If there's an error with the API request.
     */
    public function sendLocationRequest(string $to, string $body)
    {
        $message = new Message\LocationRequestMessage($to, $body, $this->reply_to);
        $request = new Request\MessageRequest\RequestLocationRequestMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a contact
     *
     * @param  string        $to    WhatsApp ID or phone number for the person you want to send a message to.
     * @param  ContactName   $name  The contact name object.
     * @param  Phone|null    $phone The contact phone number.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendContact(string $to, ContactName $name, Phone ...$phone): Response
    {
        $message = new Message\ContactMessage($to, $name, $this->reply_to, ...$phone);
        $request = new Request\MessageRequest\RequestContactMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a list
     *
     * @param  string   $to     WhatsApp ID or phone number for the person you want to send a message to.
     * @param  string   $header The header.
     * @param  string   $body   The body.
     * @param  string   $footer The footer.
     * @param  Action   $action The action object.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendList(string $to, string $header, string $body, string $footer, Action $action): Response
    {
        $message = new Message\OptionsListMessage($to, $header, $body, $footer, $action, $this->reply_to);
        $request = new Request\MessageRequest\RequestOptionsListMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a CTA URL
     *
     * @param  string   $to             WhatsApp ID or phone number for the person you want to send a message to.
     * @param  string   $displayText    The display text.
     * @param  string   $url            The URL.
     * @param  ?Header  $header         The header.
     * @param  ?string  $body           The body.
     * @param  ?string  $footer         The footer.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendCtaUrl(string $to, string $displayText, string $url, ?Header $header, ?string $body, ?string $footer): Response
    {
        $message = new Message\CtaUrlMessage($to, $displayText, $url, $header, $body, $footer, $this->reply_to);
        $request = new Request\MessageRequest\RequestCtaUrlMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    public function sendButton(string $to, string $body, ButtonAction $action, ?string $header = null, ?string $footer = null): Response
    {
        $message = new Message\ButtonReplyMessage(
            $to,
            $body,
            $action,
            $header,
            $footer,
            $this->reply_to
        );

        $request = new Request\MessageRequest\RequestButtonReplyMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Upload a media file (image, audio, video...) to Facebook servers.
     *
     * @param  string        $file_path Path of the media file to upload.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function uploadMedia(string $file_path): Response
    {
        $request = new Request\MediaRequest\UploadMediaRequest(
            $file_path,
            $this->app->fromPhoneNumberId(),
            $this->app->accessToken(),
            $this->timeout
        );

        return $this->client->uploadMedia($request);
    }

    /**
     * Sends a message with multiple products to a user.
     *
     * @param string $to The WhatsApp ID or phone number for the person you want to send a message to.
     * @param int $catalog_id The ID of the catalog where the products are located.
     * @param MultiProductAction $action The contents of the catalog products to be sent.
     * @param string $header The header of the message.
     * @param string $body The body of the message.
     * @param string|null $footer The footer of the message.
     *
     * @return Response The response object containing the result of the API request.
     *
     * @throws Response\ResponseException If the API request fails.
     */
    public function sendMultiProduct(string $to, int $catalog_id, MultiProductAction $action, string $header, string $body, ?string $footer = '')
    {
        $message = new Message\MultiProductMessage($to, $catalog_id, $action, $header, $body, $footer, $this->reply_to);
        $request = new Request\MessageRequest\RequestMultiProductMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Download a media file (image, audio, video...) from Facebook servers.
     *
     * @param  string        $media_id Id of the media uploaded with the `uploadMedia` method
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function downloadMedia(string $media_id): Response
    {
        $request = new Request\MediaRequest\DownloadMediaRequest(
            $media_id,
            $this->app->accessToken(),
            $this->timeout
        );

        return $this->client->downloadMedia($request);
    }

    /**
     * Sends a catalog message.
     *
     * @param  string         $to              WhatsApp ID or phone number for the person you want to send the message to.
     * @param  string         $body            The body of the catalog message.
     * @param  ?string        $footer          The footer of the catalog message.
     * @param  ?string        $thumbnail_product_retailer_id The product retailer ID to use as thumbnail.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendCatalog(string $to, string $body, ?string $footer = '', ?string $thumbnail_product_retailer_id = '')
    {
        $message = new Message\CatalogMessage($to, $body, $footer, $thumbnail_product_retailer_id, $this->reply_to);
        $request = new Request\MessageRequest\RequestCatalogMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Mark a message as read
     *
     * @param  string    $message_id WhatsApp Message Id will be marked as read.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function markMessageAsRead(string $message_id): Response
    {
        $request = new Request\MessageReadRequest(
            $message_id,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Sends a reaction to a provided message id.
     *
     * @param  string   $to             WhatsApp ID or phone number for the person you want to send a message to.
     * @param  string   $message_id     The ID of the message to react to.
     * @param  string   $emoji          The emoji to use as a reaction.
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function sendReaction(string $to, string $message_id, string $emoji = ''): Response
    {
        $message = new Message\ReactionMessage($to, $message_id, $emoji);

        $request = new Request\MessageRequest\RequestReactionMessage(
            $message,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->sendMessage($request);
    }

    /**
     * Get Business Profile
     *
     * @param  string    $fields WhatsApp profile fields.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function businessProfile(string $fields): Response
    {
        $request = new Request\BusinessProfileRequest\BusinessProfileRequest(
            $fields,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->businessProfile($request);
    }

    /**
     * Update Business Profile
     *
     * @param  array    $information Whatsapp profile information.
     *
     * @return Response
     *
     * @throws Response\ResponseException
     */
    public function updateBusinessProfile(array $information): Response
    {
        $request = new Request\BusinessProfileRequest\UpdateBusinessProfileRequest(
            $information,
            $this->app->accessToken(),
            $this->app->fromPhoneNumberId(),
            $this->timeout
        );

        return $this->client->updateBusinessProfile($request);
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

    /**
     * @param string $message_id    The WhatsApp Message ID to reply to.
     */
    public function replyTo(string $message_id): self
    {
        $this->reply_to = $message_id;

        return $this;
    }
}
