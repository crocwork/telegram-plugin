<?php namespace Crocwork\Telegram\Classes;

use Exception;
use Crocwork\Telegram\Models\Settings;

class Signature
{
    public static function email($auth_data)
    {
        return $auth_data['id'].'@t.me';
    }
    public static function pass($auth_data)
    {
        return crc32($auth_data['id'].'@'.$auth_data['id']);
    }
    public static function check($auth_data)
    {
        if (!Settings::get('token')) {
            throw new Exception('Please, setup bot token');
        }
        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
          $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', Settings::get('token'), true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
          throw new Exception('Data is NOT from Telegram');
        }
        if ((time() - $auth_data['auth_date']) > 86400) {
          throw new Exception('Data is outdated');
        }
        return $auth_data;
    }
}
