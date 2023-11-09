<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "info_category_informational";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('alias_url');
                $table->string('group')->nullable();
                $table->bigInteger('parent_id')->unsigned()->default(0);
                $table->tinyInteger('active')->default(1);
                $table->bigInteger('site_id');
                $table->tinyInteger('is_delete')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('site_id');
                $table->index('parent_id');
                $table->index('alias_url');
                $table->index('group');
                $table->unique(['site_id','parent_id','group','alias_url'],"unic_category_info");
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
