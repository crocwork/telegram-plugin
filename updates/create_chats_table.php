<?php namespace Croqo\Telegram\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateChatsTable Migration
 */
class CreateChatsTable extends Migration
{
    public function up()
    {
        Schema::create('croqo_telegram_chats', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigInteger('id')->unsigned()->primary();
            $table->enum('type', ['private', 'group', 'supergroup', 'channel'])->nullable()->index();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('croqo_telegram_chats');
    }
}
