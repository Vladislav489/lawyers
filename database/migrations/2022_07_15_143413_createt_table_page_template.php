<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "system_page_template";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->bigInteger('site_id')->unsigned();
                $table->bigInteger('user_id')->unsigned();
                $table->bigInteger('lang_id')->unsigned();
                $table->bigInteger('type_page_id')->unsigned();
                $table->string("template_body");
                $table->json('start_params');
                $table->tinyInteger('active');
                $table->tinyInteger('is_delete');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists($this->tableName);
    }
};
