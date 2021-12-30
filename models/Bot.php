<?php namespace Croqo\Telegram\Models;

use Croqo\Telegram\Models\User;
use October\Rain\Database\Model;
use Telegram\Bot\Api;

/**
 * Bot Model
 */
class Bot extends Model
{
    use \October\Rain\Database\Traits\Encryptable;
    protected $encryptable = ['token'];

    use \October\Rain\Database\Traits\Validation;
    public $rules = [
        'token'     => 'required|min:32'
    ];

    private Api $api;
    public function api(?string $token = null): Api
    {
        $token = $token ?? $this->token ?? null;
        if ($token)
        {
            $this->api = new Api($token);
        }
        return $this->api;
    }

    public $table = 'croqo_telegram_bots';

    protected $primaryKey = 'id';

    protected $guarded = ['*'];

    protected $fillable = [
        'token',
    ];

    protected $casts = [];
    protected $jsonable = ['data'];
    protected $visible = ['*'];
    protected $hidden = [];

    /**
     * @var array dates attributes that should be mutated to dates
     */
    protected $dates = [
        "created_at",
        "updated_at",
    ];

    public $hasOne = [
        'user'       => ['Croqo\Telegram\Models\User'],
    ];

    public function getId()
    {
        if (isset($this->id)) return $this->id;
    }
    // public function getCommandsAttribute()
    // {
    //     $result = Command::where([
    //         "bot_id" => $this->getId()
    //     ])->get();
    //     return $result;
    //     // return $this->attributes["commands"] ?? [];
    // }

    // /**
    //  * @param string $form_repeater - JSON
    //  */
    // public function setCommandsAttribute($form_repeater)
    // {
    //     if ($id = $this->getId())
    //     {
    //         $collection = new Collection(
    //             // json_decode($form_repeater, true)
    //             $form_repeater
    //         );
    //         foreach($collection as $item)
    //         {
    //             $command = Command::firstOrNew([
    //                 'command' => $item['command'],
    //                 'bot_id' => $id
    //             ]);
    //             $command->fill($item);
    //             $command->save();
    //         }
    //     }
    //     trace_log($this->commands);
    // }

    public function scopeEnabled($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * --> $this->webhook
     */
    // public function getWebhookAttribute(): WebhookInfo|null
    // {
    //     $result = null;
    //     if (isset($this->id))
    //     {
    //         $result = $this->api()->getWebhookinfo();
    //     }
    //     return $result;
    // }
    // public function setWebhookAttribute(string $id): void
    // {
    //     $this->api()->setWebhook([
    //         'url' => (string) url('tg/'.$id)
    //     ]);
    // }

    /**
     * --> $this->token
     */
    // public function getTokenAttribute(): string
    // {
    //     if (isset($this->info) && isset($this->key))
    //     {
    //         return (string) $this->info['id'] . ':' . $this->key;
    //     }
    //     else
    //     {
    //         return (string) $this->getOriginalPurgeValue('token');
    //     }
    // }
    // public function setTokenAttribute(string $token): void
    // {
    //     $a = explode(':', $token);
    //     // $this->id = $a[0];
    //     $this->key = $a[1];
    // }


    public function beforeCreate(): void
    {
        if ($token = (string) $this->token)
        {
            if ($bot = $this->api($token)->getMe())
            {
                $user = User::firstOrNew(['id'=>$bot->getId()]);
                $user->fill([
                    "is_bot"        => $bot->getIsBot(),
                    "first_name"    => $bot->getFirstName(),
                    "last_name"     => $bot->getLastName(),
                    "username"      => $bot->getUsername(),
                    "language_code" => $bot->getLanguageCode(),
                ]);
                $user->save();
                // $this->can_join_groups = $bot->getCanJoinGroups();
                // $this->supports_inline_queries = $bot->getSupportsInlineQueries();
                $this->user_id = $bot->getId();
                $this->is_active = true;
            }
        }
    }
    public function afterCreate()
    {
        // $this->webhook = $this->id;
    }
}
