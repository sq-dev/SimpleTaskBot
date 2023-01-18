<?php

namespace App\Console\Commands;

use App\Enums\TaskTypeEnum;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOldTasksCommand extends Command
{
    protected $signature = 'check:old-tasks';

    protected $description = 'Command check all daily tasks and change completed to false if they are older than 24 hours';

    public function handle()
    {
        if (Carbon::now()->hour !== 0) {
            return 0;
        }

        Task::where('completed', true)
            ->where('type', TaskTypeEnum::DAILY)
            ->chunk(100, function ($tasks) {
                /** @var Task $task */
                foreach ($tasks as $task) {
                    $task->update([
                        'completed' => false
                    ]);
                }
            });
    }
}
