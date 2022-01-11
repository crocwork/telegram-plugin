<?php namespace Croqo\Telegram\Models;

use Model;

class Chat extends Model
{
    public $table = 'croqo_telegram_chats';

    public $incrementing = false;

    protected $guarded = ['*'];

    protected $fillable = [
        'id',
        'type',
    ];

    protected $jsonable = ['data'];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function scopePrivate($query)
    {
        return $query->where('type', 'private');
    }
    public function scopeGroup($query)
    {
        return $query->where('type', 'group');
    }
    public function scopeSupergroup($query)
    {
        return $query->where('type', 'supergroup');
    }
    public function scopeChannel($query)
    {
        return $query->where('type', 'channel');
    }
}
