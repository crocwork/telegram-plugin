<?php namespace Crocwork\Telegram\Helpers;

use Config;
use Input;
use Telegram\Bot\Objects\Update;
use Crocwork\Telegram\Models\Update as UpdateModel;

class Webhook
{
    public static function path(): string
    {
        return Config::get('crocwork.telegram::webhook.path');
    }
    public static function url($argument): string
    {
        $path = self::path();
        $param = (string) $argument;
        return url("{$path}/{$param}");
    }
    public static function update(): UpdateModel
    {
        $input = Input::all();
        return UpdateModel::create([
            'data' => new Update( $input )
        ]);
    }
}
