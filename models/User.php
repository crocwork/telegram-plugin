<?php namespace Croqo\Telegram\Models;

use Model;
use RainLab\User\Models\UserModel;

/**
 * User Model
 */
class User extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Nullable;

    /**
     * @var string table associated with the model
     */
    public $table = 'telegram_users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The primary key for the model is not an integer.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array Nullable attributes.
     */
    protected $nullable = ['last_name', 'username', 'language_code'];

    /**
     * @var array guarded attributes aren't mass assignable
     */
    protected $guarded = [];

    /**
     * @var array fillable attributes are mass assignable
     */
    protected $fillable = [
        "id",
        "first_name",
        "last_name",
        "username",
        "language_code",
        "data",
    ];

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * @var array jsonable attribute names that are json encoded and decoded from the database
     */
    protected $jsonable = ['data'];

    /**
     * @var array appends attributes to the API representation of the model (ex. toArray())
     */
    protected $appends = [
        "id",
        "first_name",
        "last_name",
        "username",
        "language_code",
    ];

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
     * @var array Relations
     */
    public $belongsTo = [
        'user' =>
        [
            UserModel::class,
            'key' => 'id',
            'otherKey' => 'telegram_id',
        ]
    ];
}
