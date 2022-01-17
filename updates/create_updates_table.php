<?php namespace Croqo\Telegram\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateUpdatesTable Migration
 */
class CreateUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('croqo_telegram_updates', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->json('data');
        });
    }

    public function down()
    {
        Schema::dropIfExists('croqo_telegram_updates');
    }
}
