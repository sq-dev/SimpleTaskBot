<?php

namespace App\Telegram\Conversations;

use App\Models\User;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class ShowProfileConversation extends InlineMenu
{
    public function __construct(
        protected User $user
    )
    {
        parent::__construct();
    }

    public function start(Nutgram $bot)
    {
        $status = $this->user->notifications ? '✅' : '☑';
        $this->menuText('Profile')
            ->addButtonRow(
                InlineKeyboardButton::make(__('text.kbd.notification', [
                    'status' => $status
                ]), callback_data: 'some@notify')
            );
    }

    public function notify(Nutgram $bot): void
    {
        $this->user->update([
            'notifications' => !$this->user->notifications
        ]);

        $this->start($bot);
    }
}
