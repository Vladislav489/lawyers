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
        Schema::create('user_employee_service', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->text('description')->nullable()->comment('описание');
            $table->integer('price')->nullable()->comment('цена');
            $table->boolean('is_main')->default(false)->comment('основная');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_archive')->default(false)->comment('в архиве');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('service_id')->unsigned()->index()->comment('ID пользователя');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_employee_service');
    }
};
