<?php return [
    'plugin' =>
    [
        'name'                       => 'MICROⓂ️GRAM',
        'description'                => 'Telegram bot API integration',
        'author'                     => 'CROC⚒️WORK',
        'manage_settings'            => 'Manage telegram settings',
        'manage_settings_permission' => 'Can manage telegram settings',
        'view_log_permission'        => 'Can view query log',
    ],
    'bot' =>
    [
        'is'                        => 'Bot',
        'token'                     =>
        [
            'label' => 'Token',
            'comment' => 'Copy it from @BotFather',
        ],
    ],
    'controller' =>
    [
        'bot' => [
            'list' => [
                'title' => 'Manage Bots',
            ],
        ],
    ],
    'settings' =>
    [
        'token_label'               => 'Token',
        'token_comment'             => 'Copy it from @BotFather',
    ],
];
