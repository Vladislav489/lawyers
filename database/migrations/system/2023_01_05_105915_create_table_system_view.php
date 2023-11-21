<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "system_view";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->bigInteger('site_id')->unsigned();
                $table->bigInteger('user_id')->unsigned()->nullable();
                $table->bigInteger('lang_id')->unsigned();
                $table->bigInteger('type_page_id')->unsigned()->nullable();
                $table->string("name_view")->nullable();
                $table->string("url_route")->nullable();
                $table->longText("body_view")->nullable();
                $table->longText("body_script_view")->nullable();
                $table->longText("body_link_view")->nullable();
                $table->longText("body_title_view")->nullable();
                $table->longText("body_meta_view")->nullable();
                $table->longText("body_bottom_script_view")->nullable();
                $table->bigInteger('route_id')->unsigned();
                $table->tinyInteger('active')->default(1);
                $table->tinyInteger('physically')->default(0);
                $table->tinyInteger('is_delete')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('site_id');
                $table->index('route_id');
                $table->index('lang_id');
                $table->unique(['site_id','route_id','lang_id'],"unic_view");
            });
        }
    }
    public function down(){
        Schema::dropIfExists($this->tableName);
    }
};
