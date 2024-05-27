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
        Schema::create('chat_user', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->boolean('is_read')->default(false)->comment('прочитан');
            $table->boolean('is_block')->default(false)->comment('заблокирован');
            $table->boolean('is_archive')->comment('в архиве')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('chat_id')->unsigned()->index()->comment('ID чата');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_user');
    }
};
