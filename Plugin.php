<?php namespace Croqo\Telegram;

use App;
use Backend;
use Event;
use System\Classes\PluginBase;

use Croqo\Telegram\Models\Bot;
use Croqo\Telegram\Helpers\Webhook;
use Telegram\Bot\Api;

/**
 * Telegram Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [];

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Event::listen(
            'croqo.telegram.post',
            function($id)
            {
                App::bind('croqo.telegram.update', function($app){
                    return Webhook::update();
                });

                $bot = Bot::findOrFail($id);
                App::instance('croqo.telegram.bot', $bot);

                $api = new Api($bot->token);
                App::instance('croqo.telegram.api', $api);


                Event::fire('croqo.telegram.ready');
            }
        );
        Event::listen('croqo.telegram.ready', function(){
            // $api = App::make('croqo.telegram.api');
            // $bot = App::make('croqo.telegram.bot');
            $upd = App::make('croqo.telegram.update');
            $message = $upd->getMessage();
            $entities = $message->getEntities();
            $type = $message->detectType();

            if ($message->hasCommand())
            {
                Event::fire('croqo.telegram.command');
                trace_log($message);
                trace_log($entities);
            }

            switch ($upd->detectType())
            {
                // case 'message':
                // 'edited_message',
                // 'channel_post',
                // 'edited_channel_post',
                // 'inline_query',
                // 'chosen_inline_result',
                // 'callback_query',
                // 'shipping_query',
                // 'pre_checkout_query',
                // 'poll',
            }
            // $type = $upd->detectType();
            // trace_log( $type );

            // $chat = $upd->getChat();
            // trace_log( $chat );
            // $user = $upd->getFrom();
            // trace_log( $user );
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Croqo\Telegram\Components\Login' => 'telegramLogin',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'croqo.telegram.access_settings' => [
                'tab' => 'Telegram',
                'label' => 'Settings'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'telegram' => [
                'label'       => 'Telegram',
                'url'         => Backend::url('croqo/telegram/bots'),
                'icon'        => 'icon-paper-plane',
                'permissions' => ['croqo.telegram.*'],
                'order'       => 500,
                'sideMenu'    =>
                [
                    'bots'      => [
                        'label'       => 'Bots',
                        'icon'        => 'icon-paper-plane',
                        'url'         => Backend::url('croqo/telegram/bots'),
                        'permissions' => ['croqo.telegram.access_bots'],
                    ],
                ],
            ],
        ];
    }

}
