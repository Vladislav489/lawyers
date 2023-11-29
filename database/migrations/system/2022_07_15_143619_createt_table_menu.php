<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "system_menu";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->bigInteger('parent_id')->unsigned()->nullable();
                $table->bigInteger('site_id')->unsigned();
                $table->string('lable', 255);
                $table->string('url', 300);
                $table->string('icon', 300)->nullable();
                $table->integer('sort')->default(1);
                $table->bigInteger('lang_id');
                $table->tinyInteger('active')->default(1);
                $table->tinyInteger('is_delete')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            Schema::table($this->tableName, function (Blueprint $table) {
                \Illuminate\Support\Facades\DB::table($this->tableName)->insert(
                \App\Models\System\Admin\AdminMenu::defaultMenu());
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
