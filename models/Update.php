<?php namespace Croqo\Telegram\Models;

use Model;

class Update extends Model
{
    public $table = 'croqo_telegram_updates';
    protected $guarded = ['*'];
    protected $fillable = ['data'];
    protected $jsonable = ['data'];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function getDataAttribute(): array
    {
        return $this->attributes['data'] ?? [];
    }
}
