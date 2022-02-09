<?php namespace Croqo\Telegram;

use App;
use Backend;
use Event;
use System\Classes\PluginBase;

use Croqo\Telegram\Models\Bot;
use Croqo\Telegram\Helpers\Webhook;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update as TelegramUpdate;

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
        Event::listen('croqo.telegram.command', function($command){
            trace_log("Command: {$command}");
            // $bot = App::make('croqo.telegram.bot');
            // trace_log($bot);
        });
        Event::listen(
            'croqo.telegram.post',
            function($id, $update)
            {
                $bot = Bot::findOrFail($id);
                // App::instance('croqo.telegram.bot', $bot);
                trace_log($bot);
                // App::instance('croqo.telegram.update', $update);
                $data = new TelegramUpdate($update->data);
                if ($message = $data->getMessage())
                {
                    if ($message->hasCommand())
                    {
                        $text = $message->getText();
                        $entities = $message->getEntities();

                        $comm = $entities->where('type', 'bot_command')->first();
                        $command = substr($text, $comm['offset'], $comm['length']);
                        $command = trim($command, '/');
                        $array = explode('@',$command);
                        $command = $array[0];
                        Event::fire('croqo.telegram.command', [$command]);
                    }

                    $type = $message->detectType();
                    switch ($type)
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
                    trace_log( "Message type: {$type}" );

                    $chat = $message->getChat();
                    trace_log( $chat );
                    $user = $message->getFrom();
                    trace_log( $user );
                }

            }
        );
        Event::listen('croqo.telegram.update', function($update){
            // $api = App::make('croqo.telegram.api');
            $bot = App::make('croqo.telegram.bot');

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
                    'views'      => [
                        'label'       => 'Views',
                        'icon'        => 'icon-envelope-o',
                        'url'         => Backend::url('croqo/telegram/views'),
                        'permissions' => ['croqo.telegram.access_views'],
                    ],
                    'buttons'      => [
                        'label'       => 'Buttons',
                        'icon'        => 'icon-keyboard-o',
                        'url'         => Backend::url('croqo/telegram/buttons'),
                        'permissions' => ['croqo.telegram.access_views'],
                    ],
                    'updates'      => [
                        'label'       => 'Updates',
                        'icon'        => 'icon-paper-plane',
                        'url'         => Backend::url('croqo/telegram/updates'),
                        'permissions' => ['croqo.telegram.access_updates'],
                    ],
                ],
            ],
        ];
    }

}
