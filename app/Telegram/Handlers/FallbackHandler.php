<?php

namespace App\Telegram\Handlers;

use App\Services\AiService;
use SergiX44\Nutgram\Nutgram;
use Throwable;

class FallbackHandler
{
    public function __invoke(Nutgram $bot, AiService $aiService): void
    {
        try {
            $text = $aiService->complete($bot->message()->text, [
                'name' => $bot->user()->first_name,
                'id' => $bot->userId()
            ]);

            $bot->sendMessage($text);
        } catch (Throwable) {
            $bot->sendMessage(__('text.unknown'));
        }
    }
}
