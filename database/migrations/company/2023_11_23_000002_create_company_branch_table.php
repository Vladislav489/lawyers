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
        Schema::create('company_branch', function (Blueprint $table) {
            $table->id();
            $table->json('contact_info')->comment('контактная информация');
            $table->json('address_map')->comment('контактная информация');
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('company_id')->unsigned()->index()->comment('ID компании');
            $table->bigInteger('country_id')->unsigned()->index()->comment('ID страны');
            $table->bigInteger('state_id')->unsigned()->index()->comment('ID области');
            $table->bigInteger('city_id')->unsigned()->index()->comment('ID города');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_branch');
    }
};
