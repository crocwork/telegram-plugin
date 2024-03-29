<?php

use Croqo\Telegram\Helpers\Webhook;

$path = Webhook::path();

Route::post("{$path}/{id}", function ($id)
{
    $update = Webhook::update();
    trace_log($update);

    Event::fire(
        'croqo.telegram.post',
        [ $id, $update ]
    );

    // Event::fire(
    //     'croqo.telegram.update',
    //     [ $update ]
    // );

});
