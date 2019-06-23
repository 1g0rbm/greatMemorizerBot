<?php


namespace Ig0rbm\Memo\Entity\Telegram\Message;

use Symfony\Component\Validator\Constraints as Assert;
use Ig0rbm\Memo\Validator\Constraints\Telegram\Message as TelegramMessageAssert;

/**
 * @package Ig0rbm\Memo\Entity\Telegram\Message
 */
class MessageFrom
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("integer");
     *
     * @var integer
     */
    private $messageId;

    /**
     * @Assert\NotBlank
     * @TelegramMessageAssert\From
     *
     * @var From
     */
    private $from;

    /**
     * @Assert\NotBlank
     * @TelegramMessageAssert\Chat
     *
     * @var Chat
     */
    private $chat;

    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     *
     * @var int
     */
    private $date;

    /**
     * @Assert\NotBlank
     * @TelegramMessageAssert\Text
     *
     * @var string
     */
    private $text;

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     */
    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * @return From
     */
    public function getFrom(): From
    {
        return $this->from;
    }

    /**
     * @param From $from
     */
    public function setFrom(From $from): void
    {
        $this->from = $from;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     */
    public function setChat(Chat $chat): void
    {
        $this->chat = $chat;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    /**
     * @return Text
     */
    public function getText(): Text
    {
        return $this->text;
    }

    /**
     * @param Text $text
     */
    public function setText(Text $text): void
    {
        $this->text = $text;
    }
}