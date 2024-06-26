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
        Schema::create('user_employee', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('avatar_path', 128)->comment('url фотографии')->nullable();
            $table->string('license_number', 128)->comment('лицензионный номер')->nullable();
            $table->date('dt_practice_start')->comment('начало юридической практики');
            $table->integer('consultation_price')->comment('стоимость консультации')->nullable();
            $table->string('about', 500)->nullable()->comment('о себе');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_confirmed')->default(false)->comment('подтверждён');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('company_id')->unsigned()->index()->comment('ID компании')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_employee');
    }
};
