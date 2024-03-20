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
        Schema::create('vacancy', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->text('description')->comment('описание');
            $table->integer('payment')->unsigned()->comment('оплата')->nullable();
            $table->json('defendant')->comment('ответчик')->nullable();
            $table->string('status', 128)->comment('статус');
            $table->string('lawsuit_number', 40)->comment('номер юриста')->nullable();
            $table->string('address_judgment', 128)->comment('адрес суда')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            $table->boolean('is_group')->default(false)->comment('групповой иск');
            $table->boolean('is_public')->default(false)->comment('открыта');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_archive')->default(false)->comment('в архиве');
            $table->boolean('is_consultation')->default(false)->comment('консультация');

            $table->bigInteger('priority_id')->unsigned()->index()->comment('ID приоритета');
            $table->bigInteger('chat_id')->unsigned()->index()->comment('ID чата')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('service_id')->unsigned()->index()->comment('ID сервиса');
            $table->bigInteger('executor_id')->unsigned()->index()->nullable()->comment('ID сотрудника-исполнителя');
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
        Schema::dropIfExists('vacancy');
    }
};
