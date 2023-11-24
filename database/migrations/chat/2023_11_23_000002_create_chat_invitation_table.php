<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_invitation', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->text('text')->comment('текст приглашения');
            $table->boolean('is_archive')->default(false)->comment('в архиве');
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('chat_id')->unsigned()->index()->comment('ID чата');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('target_user_id')->unsigned()->index()->comment('ID пользователя (которому приглашение)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_invitation');
    }
};
