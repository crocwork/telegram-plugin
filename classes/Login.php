<?php namespace Croqo\Telegram\Classes;

use Input;
use Events;
use Exception;
use Croqo\Telegram\Classes\Signature;
use Event;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User as CmsUser;
use Croqo\Telegram\Models\User as TelegramUser;


class Login
{
    public $user;
    public $telegramUser;

    function __construct()
    {
        if ($auth_data = self::getData())
        {
            $this->user = self::getUser($auth_data);
            $this->telegramUser = self::getTelegramUser($auth_data);
        }
        else
        {
            die();
        }
    }
    private static function getData()
    {
        if (Input::get("id"))
        {
            $input = Input::all();
            Event::fire('telegram.login', $input);

            return Signature::check($input);
        }
    }

    public static function getUser(array $auth_data)
    {
        $data = $auth_data;
        $user = CmsUser::firstOrNew([
            'telegram_id' => $data['id'],
        ]);
        $user->fill([
            'telegram_id' => $data['id'],
            'name' => $user->name ?? $data['first_name'],
            'surname' => $user->surname ?? $data['last_name'] ?? null,
            'username' => $user->username ?? $data['username'] ?? null,
            'email' => $user->email ?? Signature::email($data),
        ]);
        $user->save();
        trace_log($user->id);


        if (!isset($user->id))
        {
            $user->password = $user->password_confirmation = Signature::pass($data);
        }
        return $user;
    }

    public static function getTelegramUser(array $auth_data) : \Croqo\Telegram\Models\User
    {
        $data = $auth_data;
        $user = TelegramUser::where([
            'id' => $data['id'],
        ]);
        $user->fill([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'username' => $data['username'] ?? null,
        ]);
        $user->data = $user->data ?? [];
        array_merge($user->data, [
            'photo_url' => $data['photo_url'],
            'auth_date' => $data['auth_date'],
        ]);

        $user->save();
        return $user;
    }
}
