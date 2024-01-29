<?php namespace Crocwork\Telegram\Models;

use Doctrine\DBAL\Types\BigIntType;
use Model;

class Chat extends Model
{
    public $table = 'crocwork_telegram_chats';

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
    public static function id(BigIntType $i): Chat
    {
        $chat =
            self::find($i) ??
            self::make([ 'id' => $i ])
        ;
        return $chat;
    }

}
