<?php

namespace Ig0rbm\Memo\Service\Quiz;

use Ig0rbm\Memo\Entity\Quiz\Quiz;
use Ig0rbm\Memo\Entity\Quiz\QuizStep;
use Ig0rbm\Memo\Entity\Translation\Word;
use Ig0rbm\Memo\Exception\Quiz\ResultantException;
use Ig0rbm\Memo\Service\Telegram\MessageBuilder;

use function implode;

class ResultantService
{
    private MessageBuilder $builder;

    public function __construct(MessageBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function create(Quiz $quiz): string
    {
        if (!$quiz->isComplete()) {
            ResultantException::becauseQuizIsNotComplete($quiz->getId());
        }

        $builder   = $this->builder;
        $result = $quiz->getSteps()->map(static function (QuizStep $step) use ($builder) {
            /** @var Word $translation */
            $translation = $step->getCorrectWord()->getTranslations()->first();
            $builder->appendLn($step->isCorrect() ? '✅' : '❌')
                ->append('Question word: ', MessageBuilder::BOLD)
                ->appendLn($step->getCorrectWord()->getText())
                ->append('Correct translation: ', MessageBuilder::BOLD)
                ->appendLn($translation->getText());

            return $builder->flush();
        });

        return implode(PHP_EOL, $result->toArray());
    }
}
