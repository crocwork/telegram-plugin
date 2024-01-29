<?php namespace Crocwork\Telegram\Models;

use Telegram\Bot\Objects\User as UserObject;
use Model;

class User extends Model
{
    public $table = 'crocwork_telegram_users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $guarded = ['*'];
    protected $fillable = [
        "is_bot",
        "first_name",
        "last_name",
        "username",
        "language_code",
    ];
    protected $jsonable = ['data'];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function scopeBots($query)
    {
        return $query->where('is_bot', true);
    }
    public function scopePeople($query)
    {
        return $query->where('is_bot', false);
    }
    public static function id(int $i): User
    {
        return
            self::find($i) ?:
            self::make([ 'id' => $i ])
        ;
    }
    public static function from(UserObject $user)
    {
        $result = self::id($user->getId());
        $result->fill([
            "is_bot"        => $user->getIsBot(),
            "first_name"    => $user->getFirstName(),
            "last_name"     => $user->getLastName(),
            "username"      => $user->getUsername(),
            "language_code" => $user->getLanguageCode(),
        ]);
        return $result;
    }
}
