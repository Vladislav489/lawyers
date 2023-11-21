<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName  = "system_site";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('domain_name');
                $table->bigInteger('user_main_id')->unsigned();
                $table->bigInteger('style')->unsigned()->nullable();
                $table->tinyInteger('active')->default(1);
                $table->bigInteger('lang_id')->nullable();
                $table->tinyInteger('is_delete')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('domain_name');
                $table->unique(['domain_name'],"unic_domain_name");
            });
        }

        if(!Schema::hasTable($this->tableName)) {
            Schema::table($this->tableName, function (Blueprint $table) {
                \Illuminate\Support\Facades\DB::table($this->tableName)->insert(
                    [
                        'domain_name' => $_SERVER['HTTP_HOST'],
                        'user_main_id' => 1,
                    ]
                );
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
