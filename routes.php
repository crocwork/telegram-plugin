<?php

use App;
use Event;
use Croqo\Telegram\Models\User;
use Croqo\Telegram\Classes\Login;
use Croqo\Telegram\Classes\Signature;
use Telegram\Bot\Laravel\Facades\Telegram;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User as UserModel;

Route::post('tg/{id}', function ($id)
{
    if ($update = Telegram::getWebhookUpdates())
    {
        Event::fire('telegram.webhookUpdate', [$update]);
    }
});
Route::get('/tg', function () {
    trace_log();
});
