<?php

namespace Ig0rbm\Memo\Event\Telegram;

use Symfony\Component\EventDispatcher\Event;

class BeforeParseRequestEvent extends Event
{
    public const NAME = 'bot.before_parse_request';

    /**
     * Raw json string receive from telegram
     *
     * @var string
     */
    private $request;

    public function __construct(string $request)
    {
        $this->request = $request;
    }

    public function getRequest(): string
    {
        return $this->request;
    }

    public function setRequest(string $request): void
    {
        $this->request = $request;
    }
}