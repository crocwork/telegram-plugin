<?php namespace Croqo\Telegram\Models;

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

    protected $hidden = [];

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
}
