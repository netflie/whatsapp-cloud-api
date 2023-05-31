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
     * @const int Minimum number of buttons.
     */
    private const MINIMUM_NUMBER_BUTTONS = 1;

    /**
     * @const int Maximum number of buttons.
     */
    private const MAXIMUM_NUMBER_BUTTONS = 3;

    /**
    * {@inheritdoc}
    */
    protected string $type = 'button';

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
        $this->assertButtonsIsValid($buttons);

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


    public function action(): array
    {
        $output = [];
        foreach ($this->buttons as $button){
            $output[] = $button->button();
        }

        return ['buttons' => $output];
    }



    private function assertTextIsValid(string $text): void
    {
        if (strlen($text) > self::MAXIMUM_LENGTH) {
            throw new \LengthException('The maximun length for a message text is ' . self::MAXIMUM_LENGTH . ' characters');
        }
    }

    private function assertButtonsIsValid(array $buttons): void
    {
        $count = count($buttons);
        if ($count > self::MAXIMUM_NUMBER_BUTTONS) {
            throw new \LengthException('The max allowed buttons is ' . self::MAXIMUM_NUMBER_BUTTONS);
        }

        if ($count < self::MINIMUM_NUMBER_BUTTONS) {
            throw new \LengthException('The min allowed buttons is ' . self::MINIMUM_NUMBER_BUTTONS);
        }
    }
}
