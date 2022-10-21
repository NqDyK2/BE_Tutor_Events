<?php

namespace App\Providers;

use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Semester;
use App\Observers\ClassroomObserver;
use App\Observers\LessonObserver;
use App\Observers\SemesterObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Semester::observe(SemesterObserver::class);
        Classroom::observe(ClassroomObserver::class);
        Lesson::observe(LessonObserver::class);
    }
}
