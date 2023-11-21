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
        Schema::create('vacancy_documentation', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->text('description')->comment('описание');
            $table->boolean('is_deleted')->default(false);
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('vacancy_id')->unsigned()->index()->comment('ID вакансии');
            $table->unique(['user_id', 'vacancy_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancy_documentation');
    }
};
