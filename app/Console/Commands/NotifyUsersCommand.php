<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

class NotifyUsersCommand extends Command
{
    public function __construct(
        protected Nutgram $bot
    )
    {
        parent::__construct();
    }

    protected $signature = 'notify:users';

    protected $description = 'Command send photo to users every morning';

    public function handle(): void
    {
        User::chunk(100, function ($users){
            /** @var User $user */
            foreach ($users as $user){
                $this->bot->sendMessage(__('text.come_back'), [
                    'chat_id' => $user->telegram_id
                ]);
            }
        });
    }
}
