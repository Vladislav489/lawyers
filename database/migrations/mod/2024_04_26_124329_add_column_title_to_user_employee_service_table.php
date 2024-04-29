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
        Schema::table('user_employee_service', function (Blueprint $table) {
            Schema::table('user_employee_service', function (Blueprint $table) {
                if (!Schema::hasColumn('user_employee_service', 'title')) {
                    $table->string('title')->comment('название');
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_employee_service', function (Blueprint $table) {
            if (Schema::hasColumn('user_employee_service', 'title')) {
                $table->dropColumn('title');
            }

        });
    }
};
