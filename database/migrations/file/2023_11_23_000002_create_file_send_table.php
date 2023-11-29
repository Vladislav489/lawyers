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
        Schema::create('file_send', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('name', 128);
            $table->string('virtual_path', 128)->unique()->comment('виртуальный url');
            $table->date('period_start');
            $table->date('period_end');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_archive')->default(false)->comment('в архиве');
            $table->bigInteger('file_id')->unsigned()->index()->comment('ID файла');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_send');
    }
};
