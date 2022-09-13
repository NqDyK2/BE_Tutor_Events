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
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('lession_id', 'lesson_id');
        });
        Schema::table('issues', function (Blueprint $table) {
            $table->renameColumn('lession_id', 'lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('lesson_id', 'lession_id');
        });
        Schema::table('issues', function (Blueprint $table) {
            $table->renameColumn('lesson_id', 'lession_id');
        });
    }
};
