<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonCallToAction;

class ButtonCallToActionMessage extends Message
{
    protected string $type = 'cta_url';

    /**
     * @var string|null $header Optional header text for the CTA.
     */
    private ?string $header;

    /**
     * @var string The main body text of the CTA.
     */
    private string $body;

    /**
     * @var string|null $footer Optional footer text for the CTA.
     */
    private ?string $footer;

    private ButtonCallToAction $action;

    /**
     * ButtonCallToActionMessage constructor.
     *
     * @param string $to The recipient's phone number in E.164 format.
     * @param ButtonCallToAction $action The call-to-action button object for the message.
     * @param string $body The main body text of the message.
     * @param string|null $header Optional header text for the message.
     * @param string|null $footer Optional footer text for the message.
     * @param string|null $replyTo The ID of the message to reply to.
     */
    public function __construct(string $to, ButtonCallToAction $action, string $body, ?string $header = null, ?string $footer = null, ?string $reply_to = null)
    {
        $this->action = $action;
        $this->body = $body;
        $this->header = $header;
        $this->footer = $footer;

        parent::__construct($to, $reply_to);
    }

    /**
     * Get the optional header text for the CTA.
     *
     * @return string|null
     */
    public function header(): ?string
    {
        return $this->header;
    }

    /**
     * Get the main body text of the CTA.
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * Get the call-to-action button as an array.
     *
     * @return array
     */
    public function action(): array
    {
        $action[] = [
            'display_text' => $this->action->title(),
            'url' => $this->action->destination(),
        ];

        return $action[0];
    }

    /**
     * Get the optional footer text for the CTA.
     *
     * @return string|null
     */
    public function footer(): ?string
    {
        return $this->footer;
    }
}
