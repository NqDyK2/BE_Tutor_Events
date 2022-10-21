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
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('class_location_offline');
            $table->dropColumn('class_location_online');
            $table->dropColumn('document_path');
            $table->string('class_location')->nullable();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('class_location');
            $table->dropColumn('note');
            $table->string('class_location_offline')->nullable();
            $table->string('class_location_online')->nullable();
            $table->string('document_path')->nullable();
        });
    }
};
