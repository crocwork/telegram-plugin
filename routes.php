<?php

use Croqo\Telegram\Models\User;
use Croqo\Telegram\Classes\Login;
use Croqo\Telegram\Classes\Signature;
use Telegram\Bot\Laravel\Facades\Telegram;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User as UserModel;

Route::post('/tg', function () {
    if ($update = Telegram::getWebhookUpdates())
    {
        $message = $update->getMessage();
        trace_log($message);
    }
});
Route::get('/tg', function () {
    trace_log();
});
