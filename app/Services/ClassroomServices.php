<?php

namespace App\Services;

use App\Jobs\Mail\SendMailAddTeacherJob;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Feedback;
use App\Models\Lesson;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassroomServices
{
    public function classroomsInSemester($semester_id)
    {
        $auth = Auth::user();
        $q =  Classroom::select([
            'classrooms.id',
            DB::raw('subjects.name as subject_name'),
            DB::raw('subjects.code as subject_code'),
            DB::raw('semesters.name as semester_name'),
            'classrooms.default_teacher_email',
        ])
            ->join('subjects', 'subjects.id', '=', 'classrooms.subject_id')
            ->join('semesters', 'semesters.id', '=', 'classrooms.semester_id')
            ->where('semester_id', $semester_id)
            ->withCount(['classStudents', 'lessons'])
            ->orderBy('subjects.code', 'asc');

        if ($auth->role_id != 1) {
            $q->where('classrooms.default_teacher_email', $auth->email)
                ->leftJoin('lessons', 'lessons.classroom_id', '=', 'classrooms.id')
                ->orWhere('lessons.teacher_email', $auth->email);
        }
        return $q->distinct()->get();
    }

    public function store($data)
    {
        $classroom = Classroom::where('semester_id', $data['semester_id'])
            ->where('subject_id', $data['subject_id'])
            ->first();

        if ($classroom) {
            return false;
        }

        $classroom = Classroom::create($data);
        $subject = $classroom->subject;

        if (!empty($data['default_teacher_email'])) {
            SendMailAddTeacherJob::dispatch(
                $data['default_teacher_email'],
                [
                    'subject' => $subject,
                ]
            );
        }

        return true;
    }

    public function update($data, $classroom)
    {
        if (!empty($data['default_teacher_email']) && $data['default_teacher_email'] != $classroom->default_teacher_email) {
            SendMailAddTeacherJob::dispatch(
                $data['default_teacher_email'],
                [
                    'subject' => $classroom->subject,
                ]
            );
        }

        Lesson::where('classroom_id', $classroom->id)
            ->where('attended', 0)
            ->update(['teacher_email' => $data['default_teacher_email']]);

        return $classroom->update($data);
    }

    public function destroy($classroom_id)
    {
        $extended = Classroom::where('id', $classroom_id)
            ->whereHas('lessons', function ($q) {
                $q->where('attended', true);
            })->exists();

        if ($extended) {
            return response([
                'message' => 'Lớp học đã diễn ra, không thể xóa lớp học này'
            ], 400);
        }

        Classroom::findOrFail($classroom_id)->delete();

        return response([
            'message' => 'Xóa lớp học thành công'
        ], 200);
    }

    public function storeFeedback($data, $classroom)
    {
        $user = Auth::user();
        $classStudent = ClassStudent::where('student_email', $user->email)
            ->where('classroom_id', $classroom->id)
            ->first();

        if (!$classStudent) {
            return response([
                'message' => 'Sinh viên không có trong lớp học'
            ], 400);
        }

        $feedback = Feedback::where('user_id', $user->id)->where('classroom_id', $classroom->id)->exists();
        if ($feedback) {
            return response([
                'message' => 'Bạn đã đánh giá lớp học này'
            ], 400);
        }

        $checkSemester = Semester::where('start_time', '<=', date('Y-m-d'))
            ->where('id', $classroom->semester_id)
            ->where('end_time', '>=', date('Y-m-d'))
            ->exists();

        if (!$checkSemester) {
            return response([
                'message' => 'Lớp học này không nằm trong kì học hiện tại'
            ], 400);
        }

        $data['user_id'] = $user->id;
        $data['classroom_id'] = $classroom->id;
        Feedback::create($data);

        return response([
            'message' => 'Đánh giá lớp học thành công'
        ], 200);
    }

    public function getListClassNeedFeedback()
    {
        $currentSemester = getInprogressSemester();
        $classNeedFeedback = [];

        if (!$currentSemester) {
            return [];
        }

        $classrooms = Classroom::where('semester_id', $currentSemester->id)
            ->whereHas('classStudents', function ($q) {
                return $q->where('student_email', Auth::user()->email);
            })
            ->with('subject')
            ->with('lessons', function ($q) {
                return $q->select('id', 'classroom_id', 'attended')
                    ->with('attendances', function ($q) {
                        return $q->where('student_email', Auth::user()->email)
                            ->where('status', Attendance::STATUS_PRESENT);
                    });
            })
            ->whereDoesntHave('feedback', function ($q) {
                return $q->where('user_id', Auth::id());
            })
            ->get();

        foreach ($classrooms as $classroom) {
            $totalLesson = $classroom->lessons->count();
            $attendedLesson = $classroom->lessons->where('attended', 1)->count();
            $studentPresentLesson = $classroom->lessons->filter(function ($lesson) {
                return $lesson->attendances->count();
            })->count();

            if (!$totalLesson) {
                continue;
            }

            if ($studentPresentLesson >= 3 || ($attendedLesson && floor($totalLesson / $attendedLesson) == 2 && $studentPresentLesson > 0)) {
                $classNeedFeedback[] = $classroom;
            }
        }

        return $classNeedFeedback;
        
    }
}
