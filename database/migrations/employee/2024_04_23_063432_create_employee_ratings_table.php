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
        Schema::create('employee_ratings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at');
            $table->unsignedBigInteger('vacancy_id');
            $table->text('text')->comment('текст отзыва')->nullable();
            $table->unsignedBigInteger('user_id')->comment('id клиента');
            $table->unsignedBigInteger('employee_user_id')->comment('id юриста');
            $table->unsignedSmallInteger('rating')->comment('кол-во звезд');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_ratings');
    }
};
