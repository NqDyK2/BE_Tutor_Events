<?php

namespace App\Services;

use App\Models\ClassStudent;
use App\Models\Lesson;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class LessonServices
{
    private $mailService;
    public function __construct(MailServices $mailService) {
        $this->mailService = $mailService;
    }

    public function lessonsInClassroom($classroomId)
    {
        $lesson = Lesson::select(
            'lessons.id',
            'lessons.classroom_id',
            DB::raw('subjects.name as subject_name'),
            DB::raw('subjects.code as subjects_code'),
            'lessons.start_time',
            'lessons.end_time',
            'lessons.type',
            DB::raw('lessons.class_location'),
            DB::raw('lessons.teacher_email as teacher_email'),
            DB::raw('lessons.tutor_email as tutor_email'),
            'lessons.content',
            'lessons.note',
            'lessons.attended',
        )
            ->leftJoin('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->leftJoin('subjects', 'subjects.id', 'classrooms.subject_id')
            ->where('classroom_id', $classroomId)
            ->withCount([
                'attendances as total_student',
                'attendances as attended_count' => function ($query) {
                    $query->where('status', true);
                }])
            ->orderBy('lessons.start_time', 'ASC', 'lessons.end_time', 'ASC')->get();
        return $lesson;
    }

    public function store($data)
    {
        return Lesson::create($data);
    }

    public function update($data, $lesson)
    {
        $checkTime = false;
        if ($lesson->start_time != $data['start_time'] || $lesson->end_time != $data['end_time'] || $lesson->class_location != $data['class_location']) {
            $user = Auth::user();
            $content = [
                'before_location' => $lesson->class_location,
                'after_location' => $data['class_location'],

                'before_date' => date('d-m-Y',strtotime($lesson->start_time)),
                'before_start_time' => date('H:i',strtotime($lesson->start_time)),
                'before_end_time' => date('H:i',strtotime($lesson->end_time)),

                'after_date' => date('d-m-Y',strtotime($data['start_time'])),
                'after_start_time' => date('H:i',strtotime($data['start_time'])),
                'after_end_time' => date('H:i',strtotime($data['end_time'])),

                'teacher_name' => $user->name,
                'teacher_email' => $user->email
            ];
            $checkTime = true;
        }
        $lesson->update($data);
        if ($checkTime) {
            $students = $lesson->classroom->classStudents;
            foreach ($students as $key => $student) {
                $this->mailService->sendEmail(
                    $student['student_email'],
                    $content,
                    'Lịch học đã được thay đổi',
                    'mail.change_lesson'
                );
            }
        }
        return true;
    }

    public function destroy($lesson_id)
    {
        $extended = Lesson::where('id', $lesson_id)
        ->where('attended', true)
        ->exists();

        if ($extended) {
            return response([
                'message' => 'Buổi học đã diễn ra, không thể xóa buổi học này'
            ], 400);
        }

        Lesson::where('id', $lesson_id)->delete();

        return response([
            'message' => 'Xóa buổi học thành công'
        ], 200);
    }

    public function studentSchedule()
    {
        return ClassStudent::select(
            'subjects.name as subject_name',
            'subjects.code as subject_code',
            'lessons.start_time',
            'lessons.end_time',
            'lessons.type',
            'lessons.class_location',
            'lessons.teacher_email',
            'lessons.tutor_email',
            'lessons.content',
            'lessons.note',
        )
            ->join('classrooms', 'classrooms.id', 'class_students.classroom_id')
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->leftJoin('lessons', 'classrooms.id', 'lessons.classroom_id')
            ->where('class_students.student_email', Auth::user()->email)
            ->where('class_students.is_joined', true)
            ->where('lessons.start_time', '>=', date('Y-m-d'))
            ->orderBy('lessons.start_time', 'ASC', 'lessons.end_time', 'ASC')
            ->get();
    }

    public function teacherTutorSchedule()
    {
        $user = Auth::user();
        return Lesson::select(
            'subjects.name as subject_name',
            'subjects.code as subject_code',
            'lessons.start_time',
            'lessons.end_time',
            'lessons.type',
            'lessons.class_location',
            'lessons.teacher_email',
            'lessons.tutor_email',
            'lessons.content',
            'lessons.note',
        )
        ->join('classrooms', 'classrooms.id', 'lessons.classroom_id')
        ->join('subjects', 'subjects.id', 'classrooms.subject_id')
        ->where('lessons.teacher_email', $user->email)
        ->orWhere('lessons.tutor_email', $user->email)
        ->where('lessons.start_time', '>=', date('Y-m-d'))
        ->orderBy('lessons.start_time', 'ASC', 'lessons.end_time', 'ASC')
        ->get();
    }

    public function getAttendanceDetail($lessonId)
    {
        $lesson = Lesson::select()
        ->where()
        ->with;
    }
}
