<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Issue;
use App\Models\Lesson;
use App\Models\Major;
use App\Models\SchoolTeacher;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Major::factory(30)->create();
        User::factory(30)->create();
        Classroom::factory(30)->create();
        Subject::factory(30)->create();
        Semester::factory(10)->create();
        Lesson::factory(50)->create();
        ClassStudent::factory(30)->create();
        SchoolTeacher::factory(30)->create();
        Issue::factory(50)->create();
        Attendance::factory(50)->create();
    }
}
