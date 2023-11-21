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
        Schema::create('user_employee_review', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->tinyInteger('grade')->comment('оценка');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя (текущего)');
            $table->bigInteger('target_user_id')->unsigned()->index()->comment('ID пользователя (на которого отзыв)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_employee_review');
    }
};
