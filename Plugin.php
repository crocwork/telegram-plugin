<?php namespace Croqo\Telegram;

use App;
use Backend;
use Event;
use System\Classes\PluginBase;
use RainLab\User\Models\User;

use Croqo\Telegram\Models\User as TelegramUser;
use Croqo\Telegram\Models\Bot;
use Croqo\Telegram\Classes\Action;
use Croqo\Telegram\Helpers\Update;


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
        Event::listen('croqo.telegram.update', function($id) {
            trace_log($id);

            if ($bot = Bot::find($id)){
                App::instance('croqo.telegram.bot', $bot);
            } else die;

            $act = Action::init();
            trace_log($act);

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
