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
        $listIdUser = $lesson->select('class_students.student_email', 'lessons.classroom_id', 'class_students.classroom_id')
            ->join('classrooms', 'classrooms.id', '=', 'lessons.classroom_id')
            ->join('class_students', 'class_students.classroom_id', '=', 'classrooms.id')
            ->where('lessons.id', $lesson->id)
            ->get();

        foreach ($listIdUser as $user) {
            $array_attendances[] = [
                'lesson_id' => $lesson->id,
                'student_email' => $user->student_email,
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
