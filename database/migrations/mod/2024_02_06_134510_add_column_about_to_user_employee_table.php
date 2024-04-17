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
        Schema::table('user_employee', function (Blueprint $table) {
            if (!Schema::hasColumn('user_employee', 'about')) {
                $table->string('about', 500)->nullable();
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
        Schema::table('user_employee', function (Blueprint $table) {
            $table->dropColumn('about');
        });
    }
};
