<?php

namespace Ig0rbm\Memo\Controller\Webhook;

use Ig0rbm\Memo\Service\Telegram\BotService;
use Ig0rbm\Memo\Service\Telegram\TokenChecker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @Route("/webhook")
 * @package Ig0rbm\Memo\Controller\Webhook
 */
class WebhookController
{
    /**
     * @var BotService
     */
    private $bot;

    /**
     * @var TokenChecker
     */
    private $tokenChecker;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        BotService $bot,
        TokenChecker $tokenChecker,
        LoggerInterface $logger
    ) {
        $this->bot = $bot;
        $this->tokenChecker = $tokenChecker;
        $this->logger = $logger;
    }

    /**
     * @Route("/bot/memo/{token}", name="webhook_bot", methods={"GET", "POST"})
     *
     * @param string $token
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function indexAction(string $token, Request $request): JsonResponse
    {
        if (false === $this->tokenChecker->isValidToken($token)) {
            return new JsonResponse(
                ['ok' => false, 'message' => 'Wrong token'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        $this->logger->debug('Message: ', ['message' => $request->getContent()]);

        try {
            $this->bot->handle($request->getContent());
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            return new JsonResponse(['ok' => false]);
        }

        return new JsonResponse(['ok' => true]);
    }
}