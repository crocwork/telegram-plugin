<?php namespace Croqo\Telegram\Models;

use Croqo\Telegram\Classes\CommandScope;
use Croqo\Telegram\Helpers\Webhook;
use Croqo\Telegram\Models\User;
use October\Rain\Database\Model;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\BotCommand;

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

    private User $user;
    public function getUserAttribute(): User
    {
        if ($user = $this->attributes['user']){
            return $user;
        } else {
            $id = $this->getId();
            $user = User::id($id);
            return $user;
        }
    }

    public function api()
    {
        return new Api($this->token, false);
    }

    public $table = 'croqo_telegram_bots';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $guarded = ['*'];
    protected $fillable = [
        'is_active',
        'data'
    ];

    protected $jsonable = ['data'];
    protected $visible = ['*'];

    /**
     * @var array dates attributes that should be mutated to dates
     */
    protected $dates = [
        "created_at",
        "updated_at",
    ];

    public function getIdAttribute(): int
    {
        $this->attributes['id'] = $result
            = self::tokenId($this->token);
        if ($result) return $result;
    }
    public function getId(): int
    {
         return $this->id;
    }

    public function getCommandsAttribute()
    {
        $array = $this->data['commands'];
        $result = [];
        foreach ($array as $i)
        {
            array_push($result, new BotCommand($i));
        }
        return $result;
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
            $url = Webhook::url($this->getId());
            $this->api()->setWebhook([
                'url' => $url
            ]);
        }
        else
        {
            $this->api()->deleteWebhook();
        }
    }

    public function beforeCreate(): void
    {
        $this->tokenSeed((string) $this->token);
    }
    public function afterCreate(): void
    {
    }

    public function afterUpdate()
    {
        $this->api()->setMyCommands([
            'commands' => $this->commands ?? [],
            'scope' => new CommandScope('default')
        ]);
}

    private function tokenSeed(string $token)
    {
        if ($bot = $this->api($token)->getMe())
        {
            $this->token = $token;
            $this->id = $bot->getId();
            $this->webhook = $this->is_active = true;
            $this->data = [];
        }
    }
    public static function tokenId(string $token): int
    {
        $a = explode(':', $token);
        return (int) $a[0];
    }
}
