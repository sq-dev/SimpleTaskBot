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
            $aiService->complete($bot->message()->text, [
                'name' => $bot->user()->first_name,
                'id' => $bot->userId()
            ]);
        } catch (Throwable) {
            $bot->sendMessage(__('text.unknown'));
        }
    }
}
