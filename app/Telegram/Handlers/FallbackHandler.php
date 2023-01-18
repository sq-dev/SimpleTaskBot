<?php

namespace App\Telegram\Handlers;

use SergiX44\Nutgram\Nutgram;

class FallbackHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage(__('text.unknown'));
    }
}
