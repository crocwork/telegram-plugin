<?php namespace Croqo\Telegram\Models;

use Model;
use Croqo\Telegram\Classes\Data;
use Telegram\Bot\Objects\Update as UpdateObject;

/**
 * Update Model
 */
class Update extends Model
{
    public $table = 'croqo_telegram_updates';
    protected $guarded = ['*'];
    protected $fillable = ['data'];
    protected $jsonable = ['data'];

    public function getDataAttribute(): Data
    {
        $data = $this->attributes['data'] ?? [];
        return new Data($data);
    }
}
