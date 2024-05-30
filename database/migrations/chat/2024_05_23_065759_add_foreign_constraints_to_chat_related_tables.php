<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_message', function (Blueprint $table) {
            $table->foreign('chat_id')->references('id')->on('chat')->cascadeOnDelete();
        });
        Schema::table('chat_invitation', function (Blueprint $table) {
            $table->foreign('chat_id')->references('id')->on('chat')->cascadeOnDelete();
        });
        Schema::table('chat_remove', function (Blueprint $table) {
            $table->foreign('chat_id')->references('id')->on('chat')->cascadeOnDelete();
        });
        Schema::table('chat_user', function (Blueprint $table) {
            $table->foreign('chat_id')->references('id')->on('chat')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_message', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
        });
        Schema::table('chat_invitation', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
        });
        Schema::table('chat_remove', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
        });
        Schema::table('chat_user', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
        });

    }
};
