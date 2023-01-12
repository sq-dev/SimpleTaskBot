<?php

namespace App\Telegram\Handlers;

use App\Telegram\Keyboards\MainKeyboards;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Nutgram;

class CancelHandler
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(Nutgram $bot): void
    {
        try {
            $bot->message()?->delete();
        }catch (\Exception){}

        $bot->endConversation();
        $bot->clearData();
        $bot->deleteUserData('page');

        $bot->sendMessage(__('text.start', ['name' => $bot->user()->first_name]),[
            'reply_markup' => MainKeyboards::main()
        ]);
    }
}
