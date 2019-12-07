<?php

namespace Ig0rbm\Memo\TelegramAction;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Ig0rbm\Memo\Entity\Telegram\Command\Command;
use Ig0rbm\Memo\Entity\Telegram\Message\MessageFrom;
use Ig0rbm\Memo\Entity\Telegram\Message\MessageTo;
use Ig0rbm\Memo\Exception\Quiz\QuizStepException;
use Ig0rbm\Memo\Service\Quiz\QuizManager;
use Ig0rbm\Memo\Service\Quiz\QuizStepSerializer;

class QuizAction extends AbstractTelegramAction
{
    /** @var QuizManager */
    private $quizManager;

    /** @var QuizStepSerializer */
    private $serializer;

    public function __construct(
        QuizManager $quizManager,
        QuizStepSerializer $serializer
    ) {
        $this->quizManager = $quizManager;
        $this->serializer  = $serializer;
    }

    /**
     * @throws DBALException
     * @throws ORMException
     * @throws QuizStepException
     */
    public function run(MessageFrom $messageFrom, Command $command): MessageTo
    {
        $to = new MessageTo();
        $to->setChatId($messageFrom->getChat()->getId());

        $quiz = $this->quizManager->getQuizByChat($messageFrom->getChat());
        $step = $quiz->getCurrentStep();

        if (!isset($step)) {
            throw QuizStepException::becauseThereAreNotQuizSteps($quiz->getId());
        }

        $to->setText(sprintf('What is russian for "%s"', $step->getCorrectWord()->getText()));
        $to->setInlineKeyboard($this->serializer->serialize($step));

        return $to;
    }
}
