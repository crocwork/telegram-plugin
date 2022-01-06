<?php namespace Croqo\Telegram\Helpers;

use Telegram\Bot\Laravel\Facades\Telegram as Api;
use Telegram\Bot\Objects\Update as Data;

class Update
{
    public static function init(): Data
    {
        return Api::getWebhookUpdate(false);
    }
}
