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
        dd('up ok');
    }

    /**
     * Handle the Semester "trashed" event.
     *
     * @param  \App\Models\Semester  $semester
     * @return void
     */
    public function deleting(Semester $semester)
    {
        dd($semester->classrooms);
        $semester->classrooms->delete();
    }
    public function deleted(Semester $semester)
    {
        dd($semester->classrooms);
        $semester->classrooms->delete();
    }
    public function trashed(Semester $semester)
    {
        dd($semester->classrooms);
        $semester->classrooms->delete();
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
