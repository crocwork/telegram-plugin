<?php

use App;
use Event;
use Croqo\Telegram\Models\Bot;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::post('tg/{id}', function ($id)
{
    if ($update = Telegram::getWebhookUpdates())
    {
        $bot = Bot::find($id) ?? die;
        App::instance('telegram.bot', $bot);
        Event::fire('telegram.webhookUpdate', [$update]);
    }
});
Route::get('/tg', function () {
    trace_log();
});
