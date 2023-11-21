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
        Schema::create('company_rating', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('grade')->comment('оценка');
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('company_id')->unsigned()->index()->comment('ID компании');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_rating');
    }
};
