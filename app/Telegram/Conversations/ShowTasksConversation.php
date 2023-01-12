<?php

namespace App\Telegram\Conversations;

use App\Models\Task;
use App\Models\User;
use App\Telegram\Handlers\CancelHandler;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class ShowTasksConversation extends InlineMenu
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot)
    {
        $this->showTasks($bot->getUserData('page', default: 1));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function showTasks($page = 1): void
    {
        $user = app(User::class);
        $tasks = $user->tasks()
            ->orderBy('completed')
            ->orderBy('created_at')
            ->paginate(5, page: $page);

        if ($tasks->count() < 1){
            $this->bot->sendMessage(__('text.task.none'));
            return;
        }

        $this->clearButtons();

        foreach ($tasks as $task) {
            $name = $task->name;
            if ($task->completed){
                $name .= ' ✅';
            }
            $this->addButtonRow(InlineKeyboardButton::make($name, callback_data: $task->id.'@showInfo'));
        }
        if ($tasks->lastPage() > 1){
            $navButton = [];
            if ($tasks->currentPage() > 1) {
                $navButton[] = InlineKeyboardButton::make(
                    __('text.kbd.prev', ['status' => '👈']),
                    callback_data: 'prev@changePage'
                );
            } else {
                $navButton[] = InlineKeyboardButton::make(
                    __('text.kbd.prev', ['status' => '❌']),
                    callback_data: 'error@changePage'
                );
            }

            if (!$tasks->hasMorePages()) {
                $navButton[] = InlineKeyboardButton::make(
                    __('text.kbd.next', ['status' => '❌']),
                    callback_data: 'error@changePage'
                );
            } else {
                $navButton[] = InlineKeyboardButton::make(
                    __('text.kbd.next', ['status' => '👉']),
                    callback_data: 'next@changePage'
                );
            }

            $this->addButtonRow(...$navButton);
        }

        $this->addButtonRow(InlineKeyboardButton::make(__('text.kbd.close'), callback_data: 'cancel'));

        $this->menuText(__('text.task.all', [
            'page' => $tasks->currentPage()
        ]))
            ->showMenu();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function changePage(Nutgram $bot, string $step): void
    {
        $this->clearButtons();
        $currentPage = (int)$bot->getUserData('page', default: 1);

        if ($step === 'next') {
            $currentPage++;
        } elseif ($step === 'prev') {
            $currentPage--;
        }else{
            $bot->answerCallbackQuery([
                'text' => __('text.nothing'),
                'show_alert' => true
            ]);
            return;
        }

        $this->showTasks($currentPage);
        $bot->setUserData('page', $currentPage);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function showInfo(Nutgram $bot, $id): void
    {
        $task = Task::findOrFail($id);
        $this->showTaskInfo($task);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function showTaskInfo(Task $task): void
    {
        $this->clearButtons();
        $this->menuText((string) view('ru.task', compact('task')), [
            'parse_mode' => ParseMode::HTML
        ]);

        if($task->completed){
            $changeText = '✅';
        }else{
            $changeText = '☑';
        }

        $this->addButtonRow(
            InlineKeyboardButton::make($changeText, callback_data: $task->id.':update@changeStatus'),
            InlineKeyboardButton::make(__('text.kbd.delete'), callback_data: $task->id.':delete@changeStatus')
        );

        $this->addButtonRow(InlineKeyboardButton::make(__('text.kbd.back'), callback_data: $task->id.':back@changeStatus'));

        $this->showMenu();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function changeStatus(Nutgram $bot, $data): void
    {
        [$id, $handle] = explode(':', $data);

        $task = Task::find($id);

        if ($handle === 'back'){
            $this->showTasks($bot->getUserData('page'));
        }elseif ($handle === 'delete'){
            $taskDeleted = $task?->delete();
            if ($taskDeleted) {
                $bot->answerCallbackQuery([
                    'text' => __('text.task.deleted')
                ]);
                (new CancelHandler())($bot);
            }else{
                $bot->answerCallbackQuery([
                    'text' => __('text.task.deleted'),
                    'show_alert' => true
                ]);
            }
        }elseif($handle === 'update'){
            $task->completed = !$task->completed;
            $task->save();
            $this->showTaskInfo($task);
        }
    }
}
