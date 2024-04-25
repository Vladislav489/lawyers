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
        Schema::create('employee_working_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('id юзера-юриста');
            $table->time('time_from');
            $table->time('time_to');
            $table->unsignedSmallInteger('day_of_week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_working_schedules');
    }
};
