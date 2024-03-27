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
        Schema::create('vacancy_offer', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->float('payment', 8, 2)->comment('оплата');
            $table->unsignedBigInteger('employee_user_id')->comment('ID сотрудника');
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('vacancy_id')->unsigned()->index()->comment('ID вакансии');
            $table->bigInteger('employee_response_id')->unsigned()->index()->comment('ID ответа сотрудника');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy_offer');
    }
};
