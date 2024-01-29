<?php

use Crocwork\Telegram\Models\Bot;

return [
    "bots" => Bot::all('is_active', 1),
    // [
    //     "default" => [
    //         "token" => env("TELEGRAM_BOT_TOKEN", null),
    //         "certificate_path" => env("TELEGRAM_CERTIFICATE_PATH", null),
    //         "webhook_url" => env("TELEGRAM_WEBHOOK_URL", "/telegram"),
    //         "commands" => [
    //             // Acme\Project\Commands\MyTelegramBot\BotCommand::class
    //         ],
    //     ],
    // ],
    "default" => 0,
    "async_requests" => false,
    "commands" => [
        Telegram\Bot\Commands\HelpCommand::class,
    ],
    "command_groups" => [],
    "shared_groups" => [
        // 'start' => Acme\Project\Commands\StartCommand::class,
        // 'stop' => Acme\Project\Commands\StopCommand::class,
        // 'status' => Acme\Project\Commands\StatusCommand::class,
    ],
];
