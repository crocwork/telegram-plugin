<?php namespace Croqo\Telegram\Classes;

use App;
use Cache;
use Event;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update as UpdateObject;

use Croqo\Telegram\Helpers\Update;
use Croqo\Telegram\Models\Bot;

/**
 *
 */
class Action
{
    private Api $api;
    private Bot $bot;
    private UpdateObject $upd;
    public function __construct()
    {
        $this->bot = $this->bot();
        $this->api = $this->call();
        $this->upd = $this->update();
    }
    public function bot()
    {
        $this->bot = $this->bot ?? App::make('croqo.telegram.bot');
        return $this->bot;
    }
    public function call()
    {
        $this->api = $this->api ?? $this->bot->api();
        return $this->api;
    }
    public function update()
    {
        $this->upd = $this->upd ?? Update::init();
        return $this->upd;
    }
    public static function init()
    {
        $action = new Action();
        Event::fire(
            'croqo.telegram.action',
            [ $action ]
        );
        return $action;
    }
}
