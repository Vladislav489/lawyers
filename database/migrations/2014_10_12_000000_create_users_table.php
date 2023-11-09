<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $tableName = 'users';
    public function up(){

        if(!Schema::hasTable($this->tableName)) {
            Schema::create($this->tableName, function (Blueprint $table) {

                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('phone')->unique()->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->tinyInteger('is_delete')->default(0);
                $table->rememberToken();
                $table->timestamps();
            });
        }
        Schema::table($this->tableName, function (Blueprint $table) {
           try {
               \Illuminate\Support\Facades\DB::table($this->tableName)->insert(
                   ['first_name' => "root",
                       'last_name' => "root",
                       'email' => "root@" . $_SERVER['HTTP_HOST'],
                       'password' => \Illuminate\Support\Facades\Hash::make("root@" . $_SERVER['HTTP_HOST'])
                   ]
               );
           }catch (Exception $e){
               dd($e->getMessage());
           }
        });
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
