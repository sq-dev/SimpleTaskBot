<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Conversations\AddTaskConversation;
use App\Telegram\Conversations\ShowTasksConversation;
use App\Telegram\Handlers\CancelHandler;
use App\Telegram\Handlers\FallbackHandler;
use App\Telegram\Middleware\RegisterUser;

$bot->middleware(RegisterUser::class);

$bot->onText(__('text.kbd.add_task'), AddTaskConversation::class);
$bot->onText(__('text.kbd.tasks'), ShowTasksConversation::class);

//Handlers
$bot->onCommand('start', CancelHandler::class);
$bot->onText(__('text.kbd.cancel'), CancelHandler::class);
$bot->onCallbackQueryData('cancel', CancelHandler::class);

//Fallbacks
$bot->fallback(FallbackHandler::class);
