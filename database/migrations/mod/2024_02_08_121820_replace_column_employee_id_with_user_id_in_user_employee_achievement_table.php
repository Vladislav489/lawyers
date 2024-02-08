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
        Schema::table('user_employee_achievement', function (Blueprint $table) {
            $table->dropIndex('user_employee_achievement_employee_id_index');
            $table->dropColumn('employee_id');
            $table->unsignedBigInteger('user_id')->index()->nullable()->comment('ID юзера');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_employee_achievement', function (Blueprint $table) {
            $table->dropIndex('user_employee_achievement_user_id_index');
            $table->dropColumn('user_id');
            $table->bigInteger('employee_id')->unsigned()->index()->nullable()->comment('ID сотрудника');
        });
    }
};
