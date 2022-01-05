<?php namespace Croqo\Telegram\Models;

use Croqo\Telegram\Helpers\Webhook;
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
        if (isset($this->token))
        {
            $a = explode(':', $this->token);
            return (int) $a[0];
        }
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
    public function getWebhookAttribute(): \Telegram\Bot\Objects\WebhookInfo
    {
        return $this->api()->getWebhookinfo();
    }
    public function setWebhookAttribute(bool $bool): void
    {
        if ($bool)
        {
            $this->api()->setWebhook([
                'url' => Webhook::url($this->getId())
            ]);
        }
        else
        {
            $this->api()->deleteWebhook();
        }
    }

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

    public function afterUpdate()
    {
        trace_log($this->data);
        $commands = [];
        foreach ($this->data['actions'] as $action)
        {
            $group = $action['_group'];
            unset($action['_group']);
            switch ($group)
            {
                case 'bot_command':
                    array_push($commands, $action);
            }
        }
        $this->api()->setMyCommands([
            'commands' => $commands
        ]);
    }

    public function afterSave()
    {
        $this->webhook = $this->is_active;
    }
}
