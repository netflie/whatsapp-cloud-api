<?php

namespace Netflie\WhatsAppCloudApi\Request\BusinessProfileRequest;

use Netflie\WhatsAppCloudApi\Request;

final class UpdateBusinessProfileRequest extends Request
{
    /**
     * @var string About (Profile Name).
     */
    private string $about;

    /**
     * @var array Aditional information.
     */
    private array $information;

    /**
     * @var string WhatsApp Number Id from messages will sent.
     */
    private string $from_phone_number_id;

    public function __construct(string $about, array $information, string $access_token, string $from_phone_number_id, ?int $timeout = null)
    {
        $this->about = $about;
        $this->information = $information;
        $this->from_phone_number_id = $from_phone_number_id;

        parent::__construct($access_token, $timeout);
    }

    /**
     * Returns the raw form of the request.
     *
     * @return array
     */
    public function body(): array
    {
        return array_merge(
            [
                'about' => $this->about,
                'messaging_product' => 'whatsapp'
            ],
            $this->information
        );
    }

    /**
     * WhatsApp node path.
     *
     * @return string
     */
    public function nodePath(): string
    {
        return $this->from_phone_number_id . '/whatsapp_business_profile';
    }
}
