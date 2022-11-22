<?php

namespace App\Observers;

use App\Models\Semester;

class SemesterObserver
{
    /**
     * Handle the Semester "created" event.
     *
     * @param  \App\Models\Semester  $semester
     * @return void
     */
    public function created(Semester $semester)
    {
        //
    }

    /**
     * Handle the Semester "updated" event.
     *
     * @param  \App\Models\Semester  $semester
     * @return void
     */
    public function updated(Semester $semester)
    {
        //
    }

    /**
     * Handle the Semester "deleted" event.
     *
     * @param  \App\Models\Semester  $semester
     * @return void
     */
    public function deleted(Semester $semester)
    {
        $classrooms = $semester->classrooms;
        foreach ($classrooms as $classroom) {
            $classroom->delete();
        }
    }

    /**
     * Handle the Semester "restored" event.
     *
     * @param  \App\Models\Semester  $semester
     * @return void
     */
    public function restored(Semester $semester)
    {
        //
    }

    /**
     * Handle the Semester "force deleted" event.
     *
     * @param  \App\Models\Semester  $semester
     * @return void
     */
    public function forceDeleted(Semester $semester)
    {
        //
    }
}
