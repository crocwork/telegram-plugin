<?php namespace Croqo\Telegram\Models;

use October\Rain\Database\Model;
use Telegram\Bot\Api;

/**
 * Bot Model
 */
class Bot extends Model
{
    use \October\Rain\Database\Traits\Purgeable;

    private Api $api;

    public $table = 'croqo_telegram_bots';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $guarded = ['*'];
    protected $fillable = ['id', 'key'];
    protected $purgeable = ['token'];
    protected $casts = [
        'can_join_groups' => 'boolean',
        'can_read_all_group_messages' => 'boolean',
        'supports_inline_queries' => 'boolean',
        'is_active' => 'boolean',
        'commands' => 'array',
    ];

    protected $jsonable = ['commands'];
    protected $visible = ['*'];
    protected $hidden = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array hasOne and other relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getTokenAttribute(): string
    {
        if (isset($this->id) && isset($this->key))
        {
            return (string) $this->id . ':' . $this->key;
        }
        else
        {
            return (string) $this->getOriginalPurgeValue('token');
        }
    }
    public function setTokenAttribute(string $token): void
    {
        $a = explode(':', $token);
        $this->id = $a[0];
        $this->key = $a[1];
    }
    public function beforeCreate(): void
    {
        if ($token = (string) $this->token)
        {
            $b = new Api($token);
            if ($bot = $b->getMe())
            {
                $this->id = $bot->getId();
                $this->first_name = $bot->getFirstName();
                $this->last_name = $bot->getLastName();
                $this->username = $bot->getUsername();
                $this->can_join_groups = $bot->getCanJoinGroups();
                $this->supports_inline_queries = $bot->getSupportsInlineQueries();
                $this->is_active = $bot->getIsBot();
            }
        }
    }
}
