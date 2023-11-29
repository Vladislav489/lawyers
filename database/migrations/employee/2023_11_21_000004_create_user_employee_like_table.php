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
        Schema::create('user_employee_like', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя (текущего)');
            $table->bigInteger('question_id')->unsigned()->index()->comment('ID вопроса');
            $table->bigInteger('target_user_id')->unsigned()->index()->comment('ID пользователя (на которого лайк)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_employee_like');
    }
};
