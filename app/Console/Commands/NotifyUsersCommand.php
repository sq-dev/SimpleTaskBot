<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
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

    protected $description = 'Command check tasks time and notify users';

    public function handle(): void
    {
        User::where('notifications', true)
            ->with('tasks')
            ->chunk(100, function ($users) {
                /** @var User $user */
                foreach ($users as $user) {
                    if (!$user->notifications) {
                        continue;
                    }

                    $uncheckedTasks = $user->tasks()
                        ->where('completed', false);
                    $count = 0;
                    foreach ($uncheckedTasks->get() as $task) {
                        $deadline = Carbon::parse($task->deadline);
                        $now = Carbon::now();
                        $diff = $deadline->diffInMinutes($now);
                        if ($diff === 0) {
                            $count++;
                        }
                    }
                    if ($count > 0) {
                        $this->bot->sendMessage(__('text.task.send_users', [
                            'count' => $count,
                        ]), [
                            'chat_id' => $user->telegram_id,
                        ]);
                    }
                }
            });
    }
}
