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
        Schema::create('user_buy', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->float('amount', 8, 2)->comment('сумма покупки');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('type_service_id')->unsigned()->index()->comment('ID типа сервиса');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_buy');
    }
};
