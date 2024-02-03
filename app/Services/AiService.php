<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class AiService
{
    public const API_URL = 'http://5.75.253.198:10000';

    public const MODEL = 'gpt-3.5-turbo-16k-0613';

    protected string $prompt = 'Представь что ты телеграм бот созданный для напоминания задачи, пользователя зовут %s, нужно ответить как клоун';

    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::API_URL,
            'http_errors' => false,
            'verify' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     * @throws \Exception
     */
    public function complete(string $text, array $additional = [])
    {
        $name = $additional['name'] ?? 'Неизвестно';
        $id = $additional['id'] ?? random_int(1, 1000);
        $dialog = $this->getChatHistory($id);

        $dialog[] = [
            'role' => 'user',
            'content' => $text
        ];

        $response = $this->client->post('/v1/chat/completions', [
            'json' => [
                'model' => self::MODEL,
                'stream' => false,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => sprintf($this->prompt, $name)
                    ],
                    ...$dialog
                ]
            ]
        ]);

        if (count($dialog) >= 40) {
            unset($dialog[0]);
        }

        $answer = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR)['choices']['0']['message']['content'];

        $dialog[] = [
            'role' => 'assistant',
            'content' => $answer
        ];

       $this->setChatHistory($id, $dialog);

        return $answer;
    }

    public function getChatHistory(int $id): array
    {
        return Cache::get('dialogs'.$id, []);
    }

    public function setChatHistory(int $id, array $history): void
    {
        Cache::put('dialogs'.$id, $history, 60 * 60 * 24);
    }
}
