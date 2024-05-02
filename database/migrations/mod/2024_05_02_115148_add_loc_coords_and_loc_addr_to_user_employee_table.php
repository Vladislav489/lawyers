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
            if (!Schema::hasColumn('user_employee', 'location_coordinates')) {
                $table->json('location_coordinates')->nullable();
            }
            if (!Schema::hasColumn('user_employee', 'location_address')) {
                $table->string('location_address')->nullable();
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
            if (Schema::hasColumn('user_employee', 'location_coordinates')) {
                $table->dropColumn('location_coordinates');
            }
            if (Schema::hasColumn('user_employee', 'location_address')) {
                $table->dropColumn('location_address');
            }
        });
    }
};
