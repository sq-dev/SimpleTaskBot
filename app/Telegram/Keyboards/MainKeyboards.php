<?php

namespace App\Telegram\Keyboards;

use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class MainKeyboards {
    public static function main(): ReplyKeyboardMarkup {
        $markup = new ReplyKeyboardMarkup(true, false, __('text.kbd.select'));

        $markup->addRow(
            KeyboardButton::make(__('text.kbd.tasks')),
            KeyboardButton::make(__('text.kbd.add_task')),
        );
        $markup->addRow(
            KeyboardButton::make(__('text.kbd.help')),
        );

        $markup->addRow(
            KeyboardButton::make(__('text.kbd.profile'))
        );

        return $markup;
    }

    public static function cancel(): ReplyKeyboardMarkup {
        $markup = new ReplyKeyboardMarkup(true, false, __('text.kbd.select'));

        $markup->addRow(
            KeyboardButton::make(__('text.kbd.cancel'))
        );

        return $markup;
    }

}
