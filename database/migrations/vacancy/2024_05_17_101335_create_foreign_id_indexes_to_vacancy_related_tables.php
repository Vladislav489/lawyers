<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('vacancy_offer', 'vacancy_id')) {
            Schema::table('vacancy_offer', function (Blueprint $table) {
                $table->foreign('vacancy_id')->references('id')->on('vacancy')->cascadeOnDelete();
            });
        }

        if (Schema::hasColumn('vacancy_status_log', 'vacancy_id')) {
            Schema::table('vacancy_status_log', function (Blueprint $table) {
                $table->foreign('vacancy_id')->references('id')->on('vacancy')->cascadeOnDelete();
            });
        }

        if (Schema::hasColumn('vacancy_closings', 'vacancy_id')) {
            Schema::table('vacancy_closings', function (Blueprint $table) {
                $table->foreign('vacancy_id')->references('id')->on('vacancy')->cascadeOnDelete();
            });
        }

        if (Schema::hasColumn('file', 'vacancy_id')) {
            Schema::table('file', function (Blueprint $table) {
                $table->foreign('vacancy_id')->references('id')->on('vacancy')->cascadeOnDelete();
            });
        }

        if (Schema::hasColumn('employee_offer_responses', 'vacancy_id')) {
            Schema::table('employee_offer_responses', function (Blueprint $table) {
                $table->foreign('vacancy_id')->references('id')->on('vacancy')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacancy_offer', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
        });

        Schema::table('vacancy_status_log', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
        });

        Schema::table('vacancy_closings', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
        });

        Schema::table('employee_offer_responses', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
        });

        Schema::table('file', function (Blueprint $table) {
            $table->dropForeign(['vacancy_id']);
        });
    }
};
