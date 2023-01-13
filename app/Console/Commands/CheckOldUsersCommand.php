<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use SergiX44\Nutgram\Nutgram;

class CheckOldUsersCommand extends Command
{
    public function __construct(
        protected Nutgram $bot
    )
    {
        parent::__construct();
    }

    protected $signature = 'check:old-users';

    protected $description = 'Command send message to users where last activity is 2 days ago';

    public function handle(): void
    {
        User::where('last_activity', '<', now()->subDays(2))
            ->orWhere('last_activity', '<', now()->subDays(5))
            ->chunk(100, function (Collection $users) {
                foreach ($users as $user) {
                    /* @var User $user */
                    $this->bot->sendMessage(__('text.come_back'), [
                        'chat_id' => $user->telegram_id
                    ]);
                }
            });
    }
}
