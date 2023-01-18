<?php

namespace App\Telegram\Conversations;

use App\Enums\TaskTypeEnum;
use App\Models\User;
use App\Telegram\Handlers\CancelHandler;
use App\Telegram\Keyboards\MainKeyboards;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class AddTaskConversation extends InlineMenu
{
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot)
    {
        $bot->sendMessage(__('text.task.name'), [
            'reply_markup' => MainKeyboards::cancel()
        ]);

        $this->next('setName');
    }


    /**
     * @throws InvalidArgumentException
     */
    public function setName(Nutgram $bot): void
    {
        if (empty($bot->message()->text)) {
            return;
        }

        $bot->setUserData('task.name', $bot->message()->text);

        $this->menuText(__('text.task.type'))
            ->addButtonRow(InlineKeyboardButton::make(__('text.kbd.daily'), callback_data: 'daily@setType'))
            ->addButtonRow(InlineKeyboardButton::make(__('text.kbd.simple'), callback_data: 'simple@setType'))
            ->orNext('failure')
            ->showMenu();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setType(Nutgram $bot, $type): void
    {
        $bot->setUserData('task.type', $type);
        $this->clearButtons();

        if ($type === TaskTypeEnum::DAILY) {
            $this->menuText(__('text.task.deadline'))
                ->addButtonRow(InlineKeyboardButton::make('8:00', callback_data: '8:00@setDeadlineCallback'))
                ->addButtonRow(InlineKeyboardButton::make('12:00', callback_data: '12:00@setDeadlineCallback'))
                ->addButtonRow(InlineKeyboardButton::make('16:00', callback_data: '16:00@setDeadlineCallback'))
                ->addButtonRow(InlineKeyboardButton::make('18:00', callback_data: '18:00@setDeadlineCallback'))
                ->addButtonRow(InlineKeyboardButton::make(__('text.kbd.own'), callback_data: 'own@setDeadlineCallback'))
                ->addButtonRow(InlineKeyboardButton::make(__('text.kbd.skip'), callback_data: 'no@skip'))
                ->orNext('failure')
                ->showMenu();
            return;
        }

        $this->menuText(__('text.task.deadline'))
            ->addButtonRow(InlineKeyboardButton::make('1 День', callback_data: '1@setSimpleDeadline'))
            ->addButtonRow(InlineKeyboardButton::make('3 Дня', callback_data: '3@setSimpleDeadline'))
            ->addButtonRow(InlineKeyboardButton::make('6 Дней', callback_data: '6@setSimpleDeadline'))
            ->addButtonRow(InlineKeyboardButton::make('1 Неделья', callback_data: '7@setSimpleDeadline'))
            ->addButtonRow(InlineKeyboardButton::make(__('text.kbd.skip'), callback_data: 'no@skip'))
            ->orNext('failure')
            ->showMenu();
    }

    /**
     * @param Nutgram $bot
     * @throws InvalidArgumentException
     */
    public function setDescription(Nutgram $bot): void
    {
        $bot->setUserData('task.description', $bot->message()->text);
        $this->saveTask($bot);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setDeadlineCallback(Nutgram $bot, $date): void
    {
        if ($date === 'own') {
            $bot->message()?->delete();
            $bot->sendMessage(__('text.task.deadline_own', [
                'format' => 'H:i',
                'example' => '12:30'
            ]));
            $this->next('setDeadlineDaily');
            return;
        }
        $bot->setUserData('task.deadline', $date);

       $this->gotToDescription();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setSimpleDeadline(Nutgram $bot, $date): void
    {
        if ($date === 'own') {
            $bot->message()?->delete();
            $bot->sendMessage(__('text.task.deadline_own', [
                'format' => 'dd.mm.Y',
                'example' => '26.09.2023'
            ]));
            $this->next('setDeadlineDaily');
            return;
        }
        $date *= 24;
        $date = Carbon::now()->addHours($date);
        $bot->setUserData('task.deadline', $date->toDateTime());

        $this->gotToDescription();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setDeadlineDaily(Nutgram $bot): void
    {
        $time = $bot->message()?->text;
        try {
            Carbon::createFromFormat('H:i', $time);
            Carbon::parse($time);
        } catch (InvalidFormatException) {
            $bot->sendMessage(__('text.invalid_format'));
            return;
        }

        $bot->setUserData('task.deadline', $time);

        $this->gotToDescription();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function skip(Nutgram $bot, $last = 'no'): void
    {
        $bot->message()?->delete();

        if ($last === 'yes') {
            $bot->deleteUserData('task.description');
            $this->saveTask($bot);
            return;
        }

        $bot->deleteUserData('task.deadline');
        $this->gotToDescription();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function saveTask(Nutgram $bot): void
    {
        $user = app(User::class);
        $user->tasks()->create([
            'name' => $bot->getUserData('task.name'),
            'description' => $bot->getUserData('task.description'),
            'deadline' => $bot->getUserData('task.deadline'),
            'type' => $bot->getUserData('task.type')
        ]);
        $bot->sendMessage(__('text.task.done'));
        (new CancelHandler())($bot);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function gotToDescription(): void
    {
        $this->clearButtons();
        $this->menuText(__('text.task.description'))
            ->addButtonRow(InlineKeyboardButton::make(__('text.kbd.skip'), callback_data: 'yes@skip'))
            ->orNext('setDescription')
            ->showMenu(true);
    }

    public function failure(Nutgram $bot): void
    {
        $bot->sendMessage(__('text.unknown'));
    }
}
