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
        $key = $token ?? $this->token;
        $this->api = $this->api ?? new Api($key);
        return $this->api;
    }

    public $table = 'croqo_telegram_bots';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $guarded = ['*'];

    protected $fillable = [
        'token',
        'is_active'
    ];

    protected $casts = [];
    protected $jsonable = ['data'];
    protected $visible = ['*'];

    /**
     * @var array dates attributes that should be mutated to dates
     */
    protected $dates = [
        "created_at",
        "updated_at",
    ];

    public function getId()
    {
        if (isset($this->token))
        {
            $a = explode(':', $this->token);
            return (int) $a[0];
        }
    }

    public function scopeIsActive($query)
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

    public function beforeCreate(): void
    {
        if ($token = (string) $this->token)
        {
            $this->id = $this->getId();

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

                $this->is_active = $bot->getIsBot();
            }
        }
    }

    public function afterUpdate()
    {
        trace_log($this->data);
        $actions = $this->data['actions'];
        $commands = [];
        foreach ($actions as $action)
        {
            $triggers = $action['triggers'] ?? [];
            foreach ($triggers as $trigger)
            {
                $group = $trigger['_group'];
                unset($trigger['_group']);
                switch ($group)
                {
                    case 'bot_command':
                        array_push($commands, $trigger);
                }
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
