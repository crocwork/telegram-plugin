<?php namespace Croqo\Telegram\Helpers;

use Config;

class Webhook
{
    public static function path()
    {
        return Config::get('croqo.telegram::webhook.path');
    }
    public static function url($argument)
    {
        $path = self::path();
        $param = (string) $argument;
        return url("{$path}/{$param}");
    }
}
