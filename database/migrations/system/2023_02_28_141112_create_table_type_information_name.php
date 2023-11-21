<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "info_type_informational_name";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->bigInteger('type_id');
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
                $table->index('type_id');
                $table->unique(['site_id','parent_id','lang_id','name','type_id'],"unic_type_info_name");
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                try {
                    \Illuminate\Support\Facades\DB::table($this->tableName)->insert(
                        [
                            ['id' => 1,
                                'name' => 'Stocks',
                                'type_id'=>1,
                                'site_id' => 1,
                                'lang_id' => 1],
                            ['id' => 2,
                                'name' => 'Cryptocurrencies',
                                'type_id'=>2,
                                'site_id' => 1,
                                'lang_id' => 1],
                            ['id' => 3,
                                'type_id'=>3,
                                'name' => 'Currencies',
                                'site_id' => 1,
                                'lang_id' => 1],
                        ]
                    );
                }catch (\Throwable $e){
                    dd($e->getMessage());
                }
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
