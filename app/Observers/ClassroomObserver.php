<?php

namespace App\Observers;

use App\Models\Classroom;

class ClassroomObserver
{
    /**
     * Handle the Classroom "created" event.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return void
     */
    public function created(Classroom $classroom)
    {
        //
    }

    /**
     * Handle the Classroom "updated" event.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return void
     */
    public function updated(Classroom $classroom)
    {
        //
    }

    /**
     * Handle the Classroom "deleted" event.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return void
     */
    public function deleted(Classroom $classroom)
    {
        $lessons = $classroom->lessons;
        foreach ($lessons as $lesson) {
            $lesson->delete();
        }

        $students = $classroom->students;
        foreach ($students as $student) {
            $student->delete();
        }
    }

    /**
     * Handle the Classroom "restored" event.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return void
     */
    public function restored(Classroom $classroom)
    {
        //
    }

    /**
     * Handle the Classroom "force deleted" event.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return void
     */
    public function forceDeleted(Classroom $classroom)
    {
        //
    }
}
