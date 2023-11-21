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
        Schema::create('chat_message', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->text('message')->comment('текст сообщения');
            $table->json('recipients')->comment('получатели');
            $table->boolean('is_archive')->default(false)->comment('в архиве');
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('chat_id')->unsigned()->index()->comment('ID чата');
            $table->bigInteger('message_type_id')->unsigned()->index()->comment('ID типа сообщения');
            $table->bigInteger('sender_user_id')->unsigned()->index()->comment('ID пользователя (отправитель)');
            $table->bigInteger('target_user_id')->unsigned()->index()->comment('ID пользователя (получатель)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_message');
    }
};
