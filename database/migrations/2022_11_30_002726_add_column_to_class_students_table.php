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
        Schema::table('class_students', function (Blueprint $table) {
            $table->dropColumn('final_result');
        });
        Schema::table('class_students', function (Blueprint $table) {
            $table->integer('final_result')->nullable();
            $table->float('final_score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_students', function (Blueprint $table) {
            $table->dropColumn('final_result');
            $table->dropColumn('final_score');
        });
        Schema::table('class_students', function (Blueprint $table) {
            $table->boolean('final_result')->nullable();
        });
    }
};
