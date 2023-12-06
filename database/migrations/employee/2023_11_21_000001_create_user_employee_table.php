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
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('photo_path', 128)->comment('url фотографии');
            $table->string('license_number', 128)->comment('лицензионный номер');
            $table->date('practice_start')->comment('начало юридической практики');
            $table->integer('consultation_price')->comment('стоимость консультации');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_confirmed')->default(false)->comment('подтверждён');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
            $table->bigInteger('company_id')->unsigned()->index()->comment('ID компании');
            $table->bigInteger('user_type_id')->unsigned()->index()->comment('ID типа пользователя');
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
