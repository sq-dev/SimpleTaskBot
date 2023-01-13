<?php

namespace App\Telegram\Middleware;

use App\Models\User;
use Carbon\Carbon;
use SergiX44\Nutgram\Nutgram;

class RegisterUser
{
    public function __invoke(Nutgram $bot, $next): void
    {
        $user = User::where('telegram_id',$bot->userId())->get()->first();

        if (!$user){
            User::create([
                'telegram_id' => $bot->userId(),
                'name' => $bot->user()->first_name. ' ' . $bot->user()->last_name,
                'last_activity' => Carbon::now()
            ]);

            $bot->sendMessage(__('text.welcome', [
                'name' => $bot->user()->first_name,
            ]));
        }else{
            $user->touch('last_activity');
        }
        app()->instance(User::class, $user);


        $next($bot);
    }
}
