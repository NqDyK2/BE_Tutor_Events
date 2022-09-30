<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\Lesson;

class LessonObserver
{
    /**
     * Handle the Lesson "created" event.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return void
     */
    public function created(Lesson $lesson)
    {
        $array_attendances = [];
        $time = now();
        $ListClassStudent = $lesson->classroom->classStudents;
        foreach ($ListClassStudent as $key => $classStudent) {
            $array_attendances[] = [
                'lesson_id' => $lesson->id,
                'user_email' => $classStudent->user_email,
                'created_at' => $time,
                'updated_at' => $time,
            ];
        }
        Attendance::insert($array_attendances);
            
    }


    /**
     * Handle the Lesson "updated" event.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return void
     */
    public function updated(Lesson $lesson)
    {
        //
    }

    /**
     * Handle the Lesson "deleted" event.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return void
     */
    public function deleted(Lesson $lesson)
    {
        $lesson->attendances()->delete();
    }

    /**
     * Handle the Lesson "restored" event.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return void
     */
    public function restored(Lesson $lesson)
    {
        //
    }

    /**
     * Handle the Lesson "force deleted" event.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return void
     */
    public function forceDeleted(Lesson $lesson)
    {
    }
}
