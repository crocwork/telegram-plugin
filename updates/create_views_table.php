<?php namespace Croqo\Telegram\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateViewsTable Migration
 */
class CreateViewsTable extends Migration
{
    public function up()
    {
        Schema::create('croqo_telegram_views', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['text', 'default'])->default('default');
            $table->string('text')->default('');
            $table->json('keyboard');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('croqo_telegram_views');
    }
}
