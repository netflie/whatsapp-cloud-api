<?php

namespace Netflie\WhatsAppCloudApi\Message;

use Netflie\WhatsAppCloudApi\Message\ReplyButtons\ReplyButton;

final class ReplyButtonsMessage extends Message
{
    /**
     * @const int Maximum length for body message.
     */
    private const MAXIMUM_LENGTH = 4096;

    /**
    * {@inheritdoc}
    */
    protected string $type = 'interactive';

    /**
     * @var string The body of the text message.
     */
    private string $text;

    /** @var ReplyButton[] */
    private array $buttons;

    /**
     * Creates a new message of type text.
     *
     * @param string         $to
     * @param string         $text
     * @param ReplyButton[]  $buttons
     */
    public function __construct(string $to, string $text, array $buttons)
    {
        $this->assertTextIsValid($text);

        $this->text = $text;
        $this->buttons = $buttons;

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


    public function buttons(): array
    {
        return $this->buttons;
    }



    private function assertTextIsValid(string $text): void
    {
        if (strlen($text) > self::MAXIMUM_LENGTH) {
            throw new \LengthException('The maximun length for a message text is ' . self::MAXIMUM_LENGTH . ' characters');
        }
    }
}
