<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    private $tableName = "info_type_informational";
    public function up(){
        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->id();
                $table->string('alias_url')->nullable();
                $table->tinyInteger('active')->default(1);
                $table->bigInteger('parent_id')->unsigned()->default(0);
                $table->bigInteger('site_id');
                $table->tinyInteger('is_delete')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->index('site_id');
                $table->index('alias_url');
                $table->index('parent_id');
                $table->unique(['site_id','parent_id','alias_url'],"unic_type_info");
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                try {
                    \Illuminate\Support\Facades\DB::table($this->tableName)->insert(
                        [
                            ['id' => 1,
                                'alias_url' => 'stocks',
                                'site_id' => 1
                            ],
                            ['id' => 2,
                                'alias_url' => 'cryptocurrencies',
                                'site_id' => 1,
                            ],
                            ['id' => 3,
                                'alias_url' => 'currencies',
                                'site_id' => 1,
                            ],
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
