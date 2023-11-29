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
        Schema::create('vacancy_vote', function (Blueprint $table) {
            $table->id();
            $table->float('grade')->comment('оценка');
            $table->bigInteger('vacancy_id')->unsigned()->index()->comment('ID вакансии');
            $table->bigInteger('employee_id')->unsigned()->index()->comment('ID сотрудника');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy_vote');
    }
};
