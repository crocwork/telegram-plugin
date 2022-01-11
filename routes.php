<?php

use Croqo\Telegram\Helpers\Webhook;

$path = Webhook::path();

Route::post("{$path}/{id}", function ($id)
{
    Event::fire('croqo.telegram.update', [$id]);
});
