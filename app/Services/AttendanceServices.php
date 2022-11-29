<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceServices
{
    public function getListClass()
    {
        $classrooms = Classroom::select(
            'classrooms.id',
            DB::raw('subjects.name as subject_name'),
            DB::raw('subjects.code as subject_code'),
        )
            ->whereHas('semester', function ($q) {
                return $q->where('end_time', '>', now())
                    ->where('start_time', '<', now());
            })
            ->whereHas('lessons', function ($q) {
                return $q->where('teacher_email', Auth::user()->email)
                    ->orWhere('tutor_email', Auth::user()->email);
            })
            ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id')
            ->withCount('classStudents')
            ->withCount('lessons')
            ->get();

        return ($classrooms);
    }

    public function getDataByLesson(Lesson $lesson)
    {
        $attendHistoryMap = [];

        $students = ClassStudent::select(
            DB::raw('users.name as student_name'),
            DB::raw('users.code as student_code'),
            'class_students.student_email',
            'class_students.is_warning',
            'class_students.is_joined',
        )
            ->where('classroom_id', $lesson->classroom_id)
            ->where('is_warning', true)
            ->leftJoin('users', 'users.email', 'class_students.student_email')
            ->orderBy('class_students.student_email', 'ASC')
            ->get();

        if ($lesson && $lesson->attended) {
            $attendHistory = Attendance::where('lesson_id', $lesson->id)->get();
            foreach ($attendHistory as $i => $x) {
                $attendHistoryMap[$x->student_email] = $x->toArray();
            }
        }

        foreach ($students as $i => $student) {
            $checkAttended = $lesson && $lesson->attended && array_key_exists($student->student_email, $attendHistoryMap);
            $student->status = $checkAttended ? $attendHistoryMap[$student->student_email]['status'] : 1;
            $student->note = $checkAttended ? $attendHistoryMap[$student->student_email]['note'] : '';
            $students[$i] = $student;
        }

        return $students;
    }

    public function update($lessonId, $data)
    {
        $lesson = Lesson::where('id', $lessonId)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        if (!$lesson) return false;

        $emails = array_map(fn ($x) => $x['student_email'], $data);

        $presentEmails = array_filter(array_map(fn ($x) => $x['status'] == ATTENDANCE_STATUS_PRESENT && in_array($x['student_email'], $emails) ? $x['student_email'] : null, $data));
        $absentEmails = array_filter(array_map(fn ($x) => $x['status'] == ATTENDANCE_STATUS_ABSENT && in_array($x['student_email'], $emails) ? $x['student_email'] : null, $data));

        if (!$lesson->attended) {
            $array_attendances = [];

            $studentsInClass = ClassStudent::where('classroom_id', $lesson->classroom_id)->get();
            $studentsEmails = array_map(fn ($x) => $x['student_email'], $studentsInClass->toArray());

            foreach ($studentsEmails as $email) {
                $array_attendances[] = [
                    'lesson_id' => $lesson->id,
                    'student_email' => $email,
                ];
            }
            Attendance::insert($array_attendances);
        }

        $cc = Attendance::where('lesson_id', $lessonId)->whereIn('student_email', $presentEmails)->update(["status" => true]);
        Attendance::where('lesson_id', $lessonId)->whereIn('student_email', $absentEmails)->update(["status" => false]);
        $lesson->attended = true;
        $lesson->save();

        return true;
    }
}
