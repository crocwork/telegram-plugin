<?php

use Croqo\Telegram\Helpers\Webhook;
use Croqo\Telegram\Models\Update;

$path = Webhook::path();

Route::post("{$path}/{id}", function ($id)
{
    Event::fire(
        'croqo.telegram.post',
        [ $id ]
    );
    $update = Webhook::update();
    trace_log($update);
    $up = Update::create([
        'data' => Input::all()
    ]);

    Event::fire(
        'croqo.telegram.update',
        [ $update ]
    );

});
