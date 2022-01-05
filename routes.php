<?php

use Telegram\Bot\Laravel\Facades\Telegram;

$path = Config::get('croqo.telegram::webhook.path');

Route::post("{$path}/{id}", function ($id)
{
    Event::fire('croqo.telegram.update', [
        $id,
        Telegram::getWebhookUpdate(false)
    ]);
});
