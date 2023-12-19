<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tableName= "system_route";
    public function up()
    {
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->bigInteger('site_id')->unsigned();
                $table->mediumText('name_title');
                $table->string('url',500);
                $table->mediumText('alias_url')->nullable();
                $table->string('template_url',500);
                $table->bigInteger('page_id')->nullable();
                $table->string('type_page',30)->nullable();
                $table->bigInteger('lang_id');
                $table->string('check_module',400)->nullable();
                $table->tinyInteger('physically')->default(0);
                $table->tinyInteger('open')->default(0);
                $table->tinyInteger('active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('site_id');
                $table->index('url');
                $table->index('lang_id');
                $table->unique(['site_id','url','lang_id'],"unic_url_rout");
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
