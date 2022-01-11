<?php namespace Croqo\Telegram\Models;

use App;
use Croqo\Telegram\Classes\Data;
use Croqo\Telegram\Helpers\Webhook;
use Croqo\Telegram\Models\User;
use October\Rain\Database\Model;

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

    public $table = 'croqo_telegram_bots';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $guarded = ['*'];
    protected $fillable = [
        'token',
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

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * --> $this->webhook
     */
    public function getWebhookAttribute(): \Telegram\Bot\Objects\WebhookInfo
    {
        return App::make('croqo.telegram.api')->getWebhookinfo();
    }
    public function setWebhookAttribute(bool $bool): void
    {
        if ($bool)
        {
            App::make('croqo.telegram.api')->setWebhook([
                'url' => Webhook::url($this->getId())
            ]);
        }
        else
        {
            App::make('croqo.telegram.api')->deleteWebhook();
        }
    }

    public function beforeCreate(): void
    {
        $this->tokenSeed((string) $this->token);
    }

    public function afterUpdate()
    {
        // $result = $this->data ?? [];
        // foreach ($result as $item)
        // {
        //     $group = $item['_group'];
        //     unset($item['_group']);
        //     switch ($group)
        //     {
        //         default: array_push($result[$group],
        //             Trigger::command(
        //                 $item['command'],
        //                 $item['description']
        //             )
        //         );
        //     }
        // }

        // trace_log($this->data);
        // $this->api()->setMyCommands([
        //     'commands' => $result
        // ]);
    }

    // public function afterSave()
    // {

    // }

    private function tokenSeed(string $token)
    {
        if ($bot = $this->api($token)->getMe())
        {
            $this->token = $token;
            $this->id = $this->getId();
            $this->is_active = true;
            $this->data = new Data();
            $this->user = User::from($bot);
        }
    }
    public static function tokenId(string $token): int
    {
        $a = explode(':', $token);
        return (int) $a[0];
    }
}
