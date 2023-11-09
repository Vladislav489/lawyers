<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "system_style";
    public function up(){
       if(!Schema::hasTable($this->tableName)) {
           Schema::create($this->tableName, function (Blueprint $table) {
               $table->id();
               $table->string('style_name');
               $table->text('folder');
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
