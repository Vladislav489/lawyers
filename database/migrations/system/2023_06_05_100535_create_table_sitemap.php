<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "system_sitemap";
    public function up(){

        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('url', 300);
                $table->bigInteger('route_id');
                $table->bigInteger('site_id');
                $table->integer("lang_id");
                $table->tinyInteger('is_delete');
                $table->tinyInteger('cache');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('route_id');
                $table->index('site_id');
                $table->index('lang_id');
                $table->index('id');
                $table->unique(['route_id','site_id','lang_id','id'],"unic_system_sitemap");
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
