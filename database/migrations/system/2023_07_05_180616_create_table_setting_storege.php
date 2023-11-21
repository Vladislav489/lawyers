<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tableName = "system_setting_storeg";
    public function up(){

        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('key', 100);
                $table->bigInteger('site_id')->nullable();
                $table->bigInteger('user_id')->nullable();
                $table->integer("lang_id")->nullable();
                $table->longText('value')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('key');
                $table->index('site_id');
                $table->index('user_id');
                $table->index('id');
                $table->unique(['key','site_id','user_id','id'],"unic_system_setting_storeg");
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
