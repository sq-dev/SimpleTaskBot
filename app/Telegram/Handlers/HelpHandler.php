<?php

namespace App\Telegram\Handlers;

use SergiX44\Nutgram\Nutgram;

class HelpHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $bot->sendMessage(__('text.help'));
    }
}
