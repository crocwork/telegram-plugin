<?php namespace Croqo\Telegram\Models;

use Telegram\Bot\Objects\User as UserObject;
use Model;

class User extends Model
{
    public $table = 'croqo_telegram_users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $guarded = ['*'];
    protected $fillable = [
        "id",
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
    public static function from(UserObject $user): User
    {
        $user =
            self::firstOrNew([
                'id'            => $user->getId(),
                "is_bot"        => $user->getIsBot(),
                "first_name"    => $user->getFirstName(),
                "last_name"     => $user->getLastName(),
                "username"      => $user->getUsername(),
                "language_code" => $user->getLanguageCode(),
                    // $this->can_join_groups = $bot->getCanJoinGroups();
                    // $this->supports_inline_queries = $bot->getSupportsInlineQueries();
            ])
        ;
        return $user;
    }
}
