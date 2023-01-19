<?php

namespace App\Telegram\Conversations;

use App\Models\User;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class ShowProfileConversation extends InlineMenu
{

    public function start(Nutgram $bot)
    {
        $user = app(User::class);
        $status = $user->notifications ? '✅' : '☑';
        $this->menuText('Profile')
            ->addButtonRow(
                InlineKeyboardButton::make(__('text.kbd.notification', [
                    'status' => $status
                ]), callback_data: 'some@notify')
            );
    }

    public function notify(Nutgram $bot): void
    {
        $user = app(User::class);
        $user->update([
            'notifications' => !$user->notifications
        ]);

        $this->start($bot);
    }
}
