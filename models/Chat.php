<?php namespace Croqo\Telegram\Models;

use Model;

class Chat extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $table = 'croqo_telegram_chats';

    public $incrementing = false;

    protected $guarded = ['*'];

    protected $fillable = [
        'id',
        'type',
    ];

    public $rules = [];

    protected $casts = [];

    protected $jsonable = ['data'];

    protected $appends = [];

    protected $hidden = [];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

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
