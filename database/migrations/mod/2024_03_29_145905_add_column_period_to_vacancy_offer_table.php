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
        Schema::table('vacancy_offer', function (Blueprint $table) {
            $table->integer('period')->nullable()->comment('Срок исполнения');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancy_offer', function (Blueprint $table) {
            $table->dropColumn('period');
        });
    }
};
