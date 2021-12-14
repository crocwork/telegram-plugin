<?php namespace Croqo\Telegram\Models;

use Model,
    Telegram\Bot\Api,
    Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Bot Model
 */
class Bot extends Model
{
    use \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;


    /**
     * @var string table associated with the model
     */
    public $table = 'croqo_telegram_bots';

    /**
     * @var string The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * @var bool The primary key incrementing.
     */
    public $incrementing = false;

    /**
     * @var array guarded attributes aren't mass assignable
     */
    protected $guarded = ['*'];

    /**
     * @var array fillable attributes are mass assignable
     */
    protected $fillable = ['id', 'key'];

    /**
    * @var array List of attributes to purge.
    */
    protected $purgeable = ['token'];

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'can_join_groups' => 'boolean',
        'can_read_all_group_messages' => 'boolean',
        'supports_inline_queries' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
    protected $jsonable = [];

    /**
     * @var array appends attributes to the API representation of the model (ex. toArray())
     */
    protected $visible = [];

    /**
     * @var array hidden attributes removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array dates attributes that should be mutated to dates
     */
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
            return $this->getOriginalPurgeValue('token');
        }
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