<?php namespace Croqo\Telegram\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateBotsTable Migration
 */
class CreateBotsTable extends Migration
{
    public function up()
    {
        Schema::create('croqo_telegram_bots', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigInteger('id')->unsigned()->primary();
            $table->boolean('is_active')->default(false)->index();
            $table->string('token',512)->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('croqo_telegram_bots');
    }
}
