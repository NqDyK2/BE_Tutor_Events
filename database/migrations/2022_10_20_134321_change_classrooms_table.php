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
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn('default_tutor_email');
            $table->dropColumn('default_offline_class_location');
            $table->dropColumn('default_online_class_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('default_tutor_email')->nullable();
            $table->string('default_offline_class_location')->nullable();
            $table->string('default_online_class_location')->nullable();
        });
    }
};
