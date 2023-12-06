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
        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('district_id')->unsigned()->index()->comment('ID района');
            $table->bigInteger('state_id')->unsigned()->index()->comment('ID области');
            $table->bigInteger('country_id')->unsigned()->index()->comment('ID страны');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city');
    }
};
