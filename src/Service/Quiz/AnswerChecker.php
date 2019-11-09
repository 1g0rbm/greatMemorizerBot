<?php

namespace Ig0rbm\Memo\Service\Quiz;

use Ig0rbm\Memo\Service\EntityFlusher;
use Ig0rbm\Memo\Repository\Quiz\QuizRepository;
use Ig0rbm\Memo\Entity\Telegram\Message\Chat;
use Ig0rbm\Memo\Entity\Quiz\QuizStep;
use Ig0rbm\Memo\Exception\Quiz\QuizStepException;

class AnswerChecker
{
    /** @var QuizRepository */
    private $quizRepository;

    /** @var Rotator */
    private $rotator;

    /** @var EntityFlusher */
    private $flusher;

    public function __construct(QuizRepository $quizRepository, Rotator $rotator, EntityFlusher $flusher)
    {
        $this->quizRepository = $quizRepository;
        $this->rotator        = $rotator;
        $this->flusher        = $flusher;
    }

    /**
     * @throws QuizStepException
     */
    public function check(Chat $chat, string $answer): ?QuizStep
    {
        $quiz = $this->quizRepository->getIncompleteQuizByChat($chat);
        $step = $this->rotator->rotate($quiz);

        if($step === null) {
            throw QuizStepException::becauseThereAreNotUnansweredSteps($quiz->getId());
        }

        $this->do($step, $answer);

        $step = $this->rotator->rotate($quiz);
        if ($step === null) {
            $quiz->setIsComplete(true);
        }

        $this->flusher->flush();

        return $step;
    }

    private function do(QuizStep $step, string $answer): QuizStep
    {
        $correctTranslation = $step->getCorrectWord()->getTranslations()->first();

        $step->setIsCorrect($correctTranslation->getText() === $answer);
        $step->setIsAnswered(true);

        return $step;
    }
}
