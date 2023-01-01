<?php

namespace App\Http\Services;

use App\Jobs\Mail\SendMailAddTeacherJob;
use App\Jobs\Mail\SendMailAddTutorJob;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Lesson;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonServices
{
    private $mailService;

    public function __construct(MailServices $mailService)
    {
        $this->mailService = $mailService;
    }

    public function lessonsInClassroom($classroomId)
    {
        $totalStudent = ClassStudent::where('classroom_id', $classroomId)->count();

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
            'lessons.attended',
        )
            ->leftJoin('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->leftJoin('subjects', 'subjects.id', 'classrooms.subject_id')
            ->where('classroom_id', $classroomId)
            ->withCount([
                'attendances as attended_count' => function ($query) {
                    $query->where('status', true);
                }
            ])
            ->orderBy('lessons.start_time', 'ASC', 'lessons.end_time', 'ASC')
            ->get()
            ->map(function ($lesson) use ($totalStudent) {
                $lesson->total_student = $totalStudent;
                return $lesson;
            });

        return $lesson;
    }

    public function store($data)
    {
        if (!$this->checkPermissionCreateLesson($data['classroom_id'])) {
            return response([
                'message' => 'Bạn không phải người phụ trách môn',
            ], 400);
        }

        $checkLessonExist = Lesson::where('class_location', $data['class_location'])
            ->where('start_time', $data['start_time'])
            ->where('end_time', $data['end_time'])
            ->first();
        if ($checkLessonExist) {
            return response([
                'message' => 'Lớp học "' . $checkLessonExist->class_location . '" đã có lớp khác đăng ký ca ' . $data['lesson_number'] . ' từ ' . $checkLessonExist->start_time . ' đến ' .  $checkLessonExist->end_time,
            ], 400);
        }

        $checkLessonExistSemester = Semester::join('classrooms', 'classrooms.semester_id', '=', 'semesters.id')
            ->where('classrooms.id', $data['classroom_id'])
            ->where('semesters.start_time', '<=', $data['start_time'])
            ->where('semesters.end_time', '>=', $data['end_time'])
            ->first();
        if (!$checkLessonExistSemester) {
            return response([
                'message' => 'Thời gian không nằm trong kỳ học',
            ], 400);
        }

        if (!empty($data['attended']) && $data['attended'] == 1) {
            $data['attended'] == 0;
        }

        $leson = Lesson::create($data);

        if (!empty($data['tutor_email'])) {
            $hasTutorInClass = Lesson::where('classroom_id', $data['classroom_id'])
                ->where('tutor_email', $data['tutor_email'])
                ->exists();

            if (!$hasTutorInClass) {
                SendMailAddTutorJob::dispatch(
                    $data['tutor_email'],
                    [
                        'subject' => $leson->subject,
                    ]
                );
            }
        }

        return response([
            'message' => 'Tạo buổi học thành công'
        ], 201);
    }

    private function checkPermissionCreateLesson($classroomId)
    {
        $auth = Auth::user();
        if ($auth->role_id == User::ROLE_ADMIN) {
            return true;
        };
        $check = Classroom::where('id', $classroomId)
            ->where('default_teacher_email', Auth::user()->email)
            ->exists();

        if ($check) {
            return true;
        };

        return false;
    }

    public function update($data, $lesson)
    {
        $needSendMail = false;

        if (!$this->checkPermissionWithLesson($lesson)) {
            return response([
                'message' => 'Bạn không phải giảng viên của buổi học',
            ], 400);
        }

        $checkLessonExist = Lesson::where('class_location', $data['class_location'])
            ->where('start_time', $data['start_time'])
            ->where('end_time', $data['end_time'])
            ->first();
        if ($checkLessonExist) {
            return response([
                'message' => 'Lớp học "' . $checkLessonExist->class_location . '" đã có lớp khác đăng ký ca ' . $data['lesson_number'] . ' từ ' . $checkLessonExist->start_time . ' đến ' .  $checkLessonExist->end_time,
            ], 400);
        }

        $checkLessonExistSemester = Semester::join('classrooms', 'classrooms.semester_id', '=', 'semesters.id')
            ->where('classrooms.id', $data['classroom_id'])
            ->where('semesters.start_time', '<=', $data['start_time'])
            ->where('semesters.end_time', '>=', $data['end_time'])
            ->first();
        if (!$checkLessonExistSemester) {
            return response([
                'message' => 'Thời gian không nằm trong kỳ học',
            ], 400);
        }

        if ($lesson->attended) {
            $data = array(
                "content" => data_get($data, 'content')
            );
        } else {
            if (
                $lesson->start_time != $data['start_time']
                || $lesson->end_time != $data['end_time']
                || $lesson->class_location != $data['class_location']
            ) {
                $needSendMail = true;
            }
        }

        if (!empty($data['tutor_email']) && $data['tutor_email'] != $lesson->tutor_email) {
            $hasTutorInClass = Lesson::where('classroom_id', $data['classroom_id'])
                ->where('tutor_email', $data['tutor_email'])
                ->exists();

            if (!$hasTutorInClass) {
                SendMailAddTutorJob::dispatch(
                    $data['tutor_email'],
                    [
                        'subject' => $lesson->subject,
                    ]
                );
            }
        }

        if (
            !empty($data['teacher_email'])
            && $data['teacher_email'] != $lesson->teacher_email
            && $data['teacher_email'] != $lesson->classroom->default_teacher_email
        ) {
            $hasTeacherInClass = Lesson::where('classroom_id', $data['classroom_id'])
                ->where('teacher_email', $data['teacher_email'])
                ->exists();

            if (!$hasTeacherInClass) {
                SendMailAddTeacherJob::dispatch(
                    $data['teacher_email'],
                    [
                        'subject' => $lesson->subject,
                    ]
                );
            }
        }

        if (!empty($data['attended']) && $data['attended'] == 1) {
            $data['attended'] == 0;
        }

        $lesson->update($data);

        // if ($needSendMail) {
        //     $students = $lesson->classroom->classStudents;
        //     $subject = $lesson->classroom->subject;

        //     foreach ($students as $student) {
        //         SendMailChangeLessonJob::dispatch(
        //             $student['student_email'],
        //             [
        //                 'lesson' => $lesson->toArray(),
        //                 'subject' => $subject->toArray(),
        //             ]
        //         );
        //     }
        // }

        return response([
            'message' => 'Cập nhật buổi học thành công',
        ], 200);
    }

    public function startLesson($lesson)
    {
        if ($lesson->start_time > now()) {
            return response([
                'message' => 'Buổi học chưa diễn ra'
            ], 400);
        } elseif ($lesson->end_time < now()) {
            return response([
                'message' => 'Buổi học đã quá thời gian'
            ], 400);
        }

        if (!$this->checkPermissionWithLesson($lesson)) {
            return response([
                'message' => 'Bạn không phải giảng viên của buổi học',
            ], 400);
        }

        $lesson->attended = true;
        $lesson->save();

        return response([
            'message' => 'Cập nhật trạng thái buổi học thành công'
        ], 200);
    }

    private function checkPermissionWithLesson($lesson)
    {
        $auth = Auth::user();
        if ($auth->role_id == User::ROLE_ADMIN) {
            return true;
        };
        if ($lesson->teacher_email == $auth->email) {
            return true;
        };
        if ($lesson->classroom->default_teacher_email == $auth->email) {
            return true;
        }

        return false;
    }

    public function destroy($lesson)
    {
        if (!$this->checkPermissionWithLesson($lesson)) {
            return response([
                'message' => 'Bạn không phải giảng viên của buổi học',
            ], 400);
        }

        if ($lesson->attended) {
            return response([
                'message' => 'Buổi học đã diễn ra, không thể xóa buổi học này'
            ], 400);
        }

        $lesson->delete();

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
        )
            ->join('classrooms', 'classrooms.id', 'class_students.classroom_id')
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->leftJoin('lessons', 'classrooms.id', 'lessons.classroom_id')
            ->where('class_students.student_email', Auth::user()->email)
            ->where('class_students.is_joined', true)
            ->where('lessons.end_time', '>=', date('Y-m-d'))
            ->orderBy('lessons.end_time', 'ASC', 'lessons.end_time', 'ASC')
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
        )
            ->join('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->where('lessons.teacher_email', $user->email)
            ->orWhere('lessons.tutor_email', $user->email)
            ->where('lessons.end_time', '>=', date('Y-m-d'))
            ->orderBy('lessons.end_time', 'ASC', 'lessons.end_time', 'ASC')
            ->get();
    }
}
