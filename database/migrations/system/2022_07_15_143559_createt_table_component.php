<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "system_component";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('component_name', 150);
                $table->string('component_anchor', 150);
                $table->json('start_params');
                $table->tinyText('template');
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
