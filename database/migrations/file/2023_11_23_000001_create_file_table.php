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
        Schema::create('file', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('name', 128);
            $table->string('path', 128)->unique()->comment('url');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_archive')->default(false)->comment('в архиве');
            $table->boolean('is_private')->default(false)->comment('приватный');
            $table->bigInteger('user_id')->unsigned()->index()->comment('ID пользователя');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file');
    }
};
