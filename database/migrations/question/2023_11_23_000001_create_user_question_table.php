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
        Schema::create('user_question', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('name');
            $table->text('text')->comment('текст вопроса');
            $table->string('status', 128)->comment('статус');
            $table->date('period_start');
            $table->date('period_end');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_donate')->default(false)->comment('донат');
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
        Schema::dropIfExists('user_question');
    }
};
