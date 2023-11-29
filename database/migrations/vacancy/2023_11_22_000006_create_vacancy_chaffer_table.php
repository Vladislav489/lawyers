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
        Schema::create('vacancy_chaffer', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->string('status', 128)->comment('статус');
            $table->text('description')->comment('описание');

            $table->bigInteger('chat_id')->unsigned()->index()->comment('ID чата');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('winner_id')->unsigned()->index()->comment('ID победителя');
            $table->bigInteger('vacancy_id')->unsigned()->index()->comment('ID вакансии');
            $table->bigInteger('initiator_id')->unsigned()->index()->comment('ID инициатора спора');
            $table->bigInteger('support_user_id')->unsigned()->index()->comment('ID поддержки');
            $table->bigInteger('user_employee_id')->unsigned()->index()->comment('ID сотрудника');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy_chaffer');
    }
};
