<?php

namespace Ig0rbm\Memo\Service\Telegram;

use Ig0rbm\Memo\Service\Telegram\Action\ActionInterface;
use Ig0rbm\Memo\Service\Telegram\Command\CommandActionParser;
use Ig0rbm\Memo\Entity\Telegram\Command\Command;
use Ig0rbm\Memo\Service\Telegram\Command\CommandParser;

class BotService
{
    /** @var MessageParser */
    private $messageParser;

    /** @var CommandParser */
    private $commandParser;

    /** @var CommandActionParser */
    private $actionParser;

    public function __construct(
        MessageParser $messageParser,
        CommandParser $commandParser,
        CommandActionParser $actionParser
    ) {
        $this->messageParser = $messageParser;
        $this->commandParser = $commandParser;
        $this->actionParser = $actionParser;
    }

    public function handle(string $raw): void
    {
        $message = $this->messageParser->createMessage($raw);
        $command = $this->defineCommand($message->getText());
        $actionCollection = $this->actionParser->createActionList();

        /** @var ActionInterface $action */
        $action = $actionCollection->get($command->getActionClass());

        $action->run($command->getTextResponse());
    }

    private function defineCommand(string $command): Command
    {
        $commandsBag = $this->commandParser->createCommandCollection();
        if (!$commandsBag->has($command)) {
            return $commandsBag->get(Command::DEFAULT_COMMAND_NAME);
        }

        return $commandsBag->get($command);
    }
}