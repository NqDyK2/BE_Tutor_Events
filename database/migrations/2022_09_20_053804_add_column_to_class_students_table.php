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
            $table->string('reason')->nullable();
            $table->string('school_classroom')->nullable();
            $table->unsignedBigInteger('school_teacher_id')->nullable();
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
            $table->dropColumn('reason');
            $table->dropColumn('school_classroom');
            $table->dropColumn('school_teacher_id');
        });
    }
};
