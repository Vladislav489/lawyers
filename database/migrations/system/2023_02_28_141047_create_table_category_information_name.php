<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "info_category_informational_name";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->bigInteger('category_id');
                $table->bigInteger('lang_id');
                $table->bigInteger('site_id');
                $table->integer('sort')->default(0);
                $table->bigInteger('parent_id')->unsigned()->default(0);
                $table->tinyInteger('active')->default(1);
                $table->tinyInteger('is_delete')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('site_id');
                $table->index('lang_id');
                $table->index('parent_id');
                $table->index('name');
                $table->index('category_id');
                $table->unique(['site_id','parent_id','lang_id','name','category_id'],"unic_category_info_name");
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
