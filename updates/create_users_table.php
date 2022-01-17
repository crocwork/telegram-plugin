<?php namespace Croqo\Telegram\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateUsersTable Migration
 */
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('croqo_telegram_users', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigInteger('id')->unsigned()->primary();
            $table->boolean('is_bot')->default(true)->index();

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('language_code', 4)->nullable();
            $table->json('data');
            $table->timestamps();
        });

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('telegram_id')->unsigned()->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users')) {
            if (Schema::hasColumn('users', 'telegram_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('telegram_id');
                });
            }
        }
        Schema::dropIfExists('croqo_telegram_users');
    }
}
