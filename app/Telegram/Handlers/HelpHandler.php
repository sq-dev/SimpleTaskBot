<?php

namespace App\Telegram\Handlers;

use App\Services\AiService;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ChatActions;
use Throwable;

class HelpHandler
{
    public function __construct(protected AiService $aiService)
    {
    }

    public function __invoke(Nutgram $bot): void
    {
        try {
            $bot->sendChatAction(ChatActions::TYPING);

            $text = $this->aiService->complete($bot->message()->text, [
                'name' => $bot->user()->first_name,
                'id' => $bot->userId()
            ]);

            $bot->sendMessage($text);
        } catch (Throwable) {
            $bot->sendMessage(__('text.help'));
        }
    }
}
