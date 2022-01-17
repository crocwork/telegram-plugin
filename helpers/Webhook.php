<?php namespace Croqo\Telegram\Helpers;

use Config;
use Input;
use Telegram\Bot\Objects\Update;

class Webhook
{
    public static function path(): string
    {
        return Config::get('croqo.telegram::webhook.path');
    }
    public static function url($argument): string
    {
        $path = self::path();
        $param = (string) $argument;
        return url("{$path}/{$param}");
    }
    public static function update(): Update
    {
        $input = Input::all();
        return new Update( $input );
    }
}
