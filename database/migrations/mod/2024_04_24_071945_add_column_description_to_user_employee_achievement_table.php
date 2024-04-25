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
            if (!Schema::hasColumn('user_employee_achievement', 'description')) {
                $table->string('description')->nullable()->comment('описание');
            }
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
            if (Schema::hasColumn('user_employee_achievement', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
