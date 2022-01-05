<?php namespace Croqo\Telegram;

use App;
use Backend;
use Event;
use System\Classes\PluginBase;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Laravel\TelegramServiceProvider;
use RainLab\User\Models\User;
use Croqo\Telegram\Models\User as TelegramUser;

/**
 * Telegram Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['RainLab.User','RainLab.Translate'];

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        // Register the aliases provided by the packages used by your plugin
        App::registerClassAlias('Telegram', Telegram::class);

        // Register the service providers provided by the packages used by your plugin
        App::register(TelegramServiceProvider::class);
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Event::listen('croqo.telegram.update', function($id, $update) {
            trace_log($id);
            trace_log($update);
        });
        Event::listen('croqo.telegram.token.setup', function($token) {
            trace_log($token);
        });

        User::extend(function ($model) {
            $model->hasOne['telegram'] = [
                TelegramUser::class,
                'key' => 'telegram',
                'otherKey' => 'id'
            ];
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
                    // 'commands'      => [
                    //     'label'       => 'Commands',
                    //     'icon'        => 'icon-paper-plane',
                    //     'url'         => Backend::url('croqo/telegram/commands'),
                    //     'permissions' => ['croqo.telegram.access_commands'],
                    // ],
                ],
            ],
        ];
    }

}
