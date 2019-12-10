<?php

namespace Ig0rbm\Memo\Service\Translation\Yandex;

use Symfony\Component\HttpFoundation\Request;
use Ig0rbm\Memo\Entity\Translation\Direction;
use Ig0rbm\Memo\Exception\Translation\Yandex\TranslationException;
use Ig0rbm\Memo\Service\Translation\ApiWordTranslationInterface;
use Ig0rbm\Memo\Collection\Translation\WordsBag;
use GuzzleHttp\Client;
use Throwable;

class YandexDictionaryApiService implements ApiWordTranslationInterface
{
    private const LOOKUP_URI = '/api/v1/dicservice.json/lookup';

    private string $token;

    private Client $client;

    private DictionaryParser $parser;

    public function __construct(string $token, Client $client, DictionaryParser $parser)
    {
        $this->token  = $token;
        $this->client = $client;
        $this->parser = $parser;
    }

    /**
     * @throws TranslationException
     */
    public function getTranslate(Direction $direction, string $phrase): WordsBag
    {
        try {
            $response = $this->client->request(
                Request::METHOD_GET,
                static::LOOKUP_URI,
                [
                    'query' => [
                        'key' => $this->token,
                        'lang' => $direction->getDirection(),
                        'text' => $phrase
                    ]
                ]
            );
        } catch (Throwable $e) {
            throw TranslationException::becauseBadRequestFromYandexApi($e->getMessage());
        }

        return $this->parser->parse($response->getBody()->getContents(), $direction);
    }
}