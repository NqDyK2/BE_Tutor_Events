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
        $listIdUser = $lesson->select('users.id')
        ->leftJoin('classrooms','classrooms.id','lessons.classroom_id')
        ->leftJoin('class_students','class_students.classroom_id','classrooms.id')
        ->leftJoin('users','users.email','class_students.user_email')
        ->where('lessons.id',$lesson->id)
        ->get();
        foreach ($listIdUser as $key => $user) {
            $array_attendances[] = [
                'lesson_id' => $lesson->id,
                'user_id' => $user->id,
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
