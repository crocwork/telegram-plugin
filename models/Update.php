<?php namespace Crocwork\Telegram\Models;

use Model;

class Update extends Model
{
    public $table = 'crocwork_telegram_updates';
    protected $guarded = ['*'];
    protected $fillable = ['data'];
    protected $jsonable = ['data'];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getDataAttribute(): string //JSON
    {
        return $this->attributes['data'];
    }
}
