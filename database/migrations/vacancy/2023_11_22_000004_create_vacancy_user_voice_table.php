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
        Schema::create('vacancy_user_voice', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('vacancy_id')->unsigned()->index()->comment('ID вакансии');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy_user_voice');
    }
};
