<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "system_seo";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->bigInteger('site_id')->unsigned();
                $table->bigInteger('template_id')->unsigned();
                $table->bigInteger('route_id')->unsigned();
                $table->string('title', 300)->nullable();
                $table->mediumText('keywords')->nullable();
                $table->bigInteger('lang_id');
                $table->text('description')->nullable();
                $table->tinyInteger('active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('site_id');
                $table->index('template_id');
                $table->index('route_id');
                $table->index('lang_id');
                $table->unique(['site_id','template_id','route_id','lang_id'],"unic_seo");
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
