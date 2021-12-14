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
            $table->string('username')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('can_join_groups')->default(true);
            $table->boolean('can_read_all_group_messages')->default(false);
            $table->boolean('supports_inline_queries')->default(false);
            $table->boolean('is_active')->default(false);

            $table->string('key')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('croqo_telegram_bots');
    }
}
