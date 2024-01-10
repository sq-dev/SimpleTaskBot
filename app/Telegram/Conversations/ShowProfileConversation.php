<?php

namespace App\Telegram\Conversations;

use App\Models\User;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class ShowProfileConversation extends InlineMenu
{

    /**
     * @throws InvalidArgumentException
     */
    public function notify(Nutgram $bot): void
    {
        $user = app(User::class);
        $user->update([
            'notifications' => !$user->notifications
        ]);

        $this->clearButtons();
        $this->start($bot);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot)
    {
        $user = app(User::class);
        $tasks = $user->tasks();
        $profile = __('text.profile', [
            'id' => $bot->userId(),
            'date' => $user->created_at->format('d.m.Y'),
            'total' => $tasks->count(),
            'done' => $tasks->where('completed', true)->count(),
            'left' => $tasks->where('completed', false)->count(),
        ]);
        $this->menuText($profile)
            ->addButtonRow(
                InlineKeyboardButton::make(__('text.kbd.notification', [
                    'status' => $user->notifications ? '✅' : '☑',
                ]), callback_data: 'some@notify')
            )->showMenu();
    }
}
