<?php namespace Croqo\Telegram\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateButtonsTable Migration
 */
class CreateButtonsTable extends Migration
{
    public function up()
    {
        Schema::create('croqo_telegram_buttons', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', [
                'url',
                'login_url',
                'callback_data',
                'switch_inline_query',
                'switch_inline_query_current_chat',
                'callback_game',
                'pay',
            ])->default('callback_data');
            $table->string('text')->default(' *️⃣ ');
            $table->string('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('croqo_telegram_buttons');
    }
}
