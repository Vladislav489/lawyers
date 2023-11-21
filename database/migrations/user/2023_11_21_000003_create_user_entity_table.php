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
        Schema::create('user_entity', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->string('email', 128)->unique()->comment('электронная почта');
            $table->string('password', 255)->comment('хэш-пароля');

            $table->string('first_name', 64)->comment('имя');
            $table->string('last_name', 64)->comment('фамилия');
            $table->string('post_code', 7)->comment('почтовый индекс');
            $table->string('phone_number', 20)->unqique()->comment('номер телефона');
            $table->date('date_birthday')->comment('дата рождения');
            $table->dateTime('online')->nullable()->comment('был в сети');

            $table->boolean('is_block')->default(false)->comment('заблокирован');
            $table->boolean('is_public')->default(false)->comment('открытый профиль');
            $table->boolean('is_deleted')->default(false);

            $table->bigInteger('country_id')->unsigned()->index()->comment('ID страны');
            $table->bigInteger('state_id')->unsigned()->index()->comment('ID области');
            $table->bigInteger('city_id')->unsigned()->index()->comment('ID города');
            $table->bigInteger('user_type_id')->unsigned()->index()->comment('ID типа пользоватедя');
            $table->bigInteger('user_modifier_id')->unsigned()->index()->comment('ID модификатора пользоватедя');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_entity');
    }
};
