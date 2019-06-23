<?php

namespace Ig0rbm\Memo\Service\Telegram;

use Ig0rbm\Memo\Entity\Telegram\Message\Chat;
use Ig0rbm\Memo\Entity\Telegram\Message\From;
use Ig0rbm\Memo\Entity\Telegram\Message\MessageFrom;
use Ig0rbm\Memo\Exception\Telegram\Message\ParseMessageException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessageParser
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var TextParser */
    private $textParser;

    public function __construct(ValidatorInterface $validator, TextParser $textParser)
    {
        $this->validator = $validator;
        $this->textParser = $textParser;
    }

    public function createMessage(string $message): MessageFrom
    {
        $messageRaw = json_decode($message, true);
        $messageRaw = $messageRaw['message'];

        $chat = $this->createChat($messageRaw['chat']);
        $from = $this->createFrom($messageRaw['from']);

        $message = new MessageFrom();
        $message->setMessageId($messageRaw['message_id']);
        $message->setChat($chat);
        $message->setFrom($from);
        $message->setDate($messageRaw['date']);
        $message->setText($this->textParser->parse($messageRaw['text']));

        $this->validate($message);

        return $message;
    }

    /**
     * @param array $chatRaw
     * @return Chat
     */
    private function createChat(array $chatRaw): Chat
    {
        $chat = new Chat();
        $chat->setId($chatRaw['id']);
        $chat->setType($chatRaw['type']);
        $chat->setFirstName($chatRaw['first_name']);
        $chat->setLastName($chatRaw['last_name']);
        $chat->setUsername($chatRaw['username']);

        $this->validate($chat);

        return $chat;
    }

    private function createFrom(array $fromRaw): From
    {
        $from = new From();
        $from->setId($fromRaw['id']);
        $from->setIsBot($fromRaw['is_bot']);
        $from->setFirstName($fromRaw['first_name']);
        $from->setLastName($fromRaw['last_name']);
        $from->setUsername($fromRaw['username']);
        $from->setLanguageCode($fromRaw['language_code']);

        $this->validate($from);

        return $from;
    }

    /**
     * @param Chat|From|MessageFrom $value
     */
    private function validate($value)
    {
        $errors = $this->validator->validate($value);
        if (count($errors) > 0) {
            throw ParseMessageException::becauseInvalidParameter((string)$errors);
        }
    }
}