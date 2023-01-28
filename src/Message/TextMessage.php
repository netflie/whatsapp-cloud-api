<?php

namespace Netflie\WhatsAppCloudApi\Message;

final class TextMessage extends Message
{
    /**
     * @const int Maximum length for body message.
     */
    private const MAXIMUM_LENGTH = 4096;

    /**
    * {@inheritdoc}
    */
    protected string $type = 'text';

    /**
     * @var string The body of the text message.
     */
    private string $text;

    /**
     * @var bool Determines if show a preview box for URLs contained in the text message.
     */
    private bool $preview_url;

    /**
     * Creates a new message of type text.
     *
     * @param string         $to
     * @param string         $text
     * @param bool           $preview_url
     */
    public function __construct(string $to, string $text, bool $preview_url = false)
    {
        $this->assertTextIsValid($text);

        $this->text = $text;
        $this->preview_url = $preview_url;

        parent::__construct($to);
    }

    /**
     * Return the body of the text message.
     *
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

    /**
     * Return if preview box for URLs contained in the text message is shown.
     *
     * @return bool
     */
    public function previewUrl(): bool
    {
        return $this->preview_url;
    }

    private function assertTextIsValid(string $text): void
    {
        if (strlen($text) > self::MAXIMUM_LENGTH) {
            throw new \LengthException('The maximun length for a message text is ' . self::MAXIMUM_LENGTH . ' characters');
        }
    }
}
