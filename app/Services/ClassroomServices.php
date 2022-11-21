<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Lesson;
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
            ->join('lessons', 'lessons.classroom_id', '=', 'classrooms.id')
            ->where('semester_id', $semester_id)
            ->withCount(['classStudents', 'lessons'])
            ->orderBy('subjects.code', 'asc');

        if ($auth->role_id != 1) {
            $q->where('classrooms.default_teacher_email', $auth->email)
                ->orWhere('lessons.teacher_email', $auth->email);
        }
        return $q->get();
    }

    public function store($data)
    {
        $existsClassroom = Classroom::where('semester_id', $data['semester_id'])
            ->where('subject_id', $data['subject_id'])
            ->exists();

        if ($existsClassroom) return false;

        return Classroom::create($data);
    }

    public function update($data, $classroom)
    {
        Lesson::where('classroom_id', $classroom->id)
            ->where('attended', false)
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

        Classroom::where('id', $classroom_id)->delete();

        return response([
            'message' => 'Xóa lớp học thành công'
        ], 200);
    }

    public function studentMissingClasses()
    {
        return Classroom::select(
            'classrooms.id',
            'subjects.name',
            'subjects.code',
        )
            ->whereHas('classStudents', function ($q) {
                return $q->where('student_email', Auth::user()->email)
                    ->where('is_joined', false);
            })
            ->join('subjects', 'subjects.id', 'classrooms.subject_id')
            ->get();
    }

    public function joinClass($classroomId)
    {
        $classroom = ClassStudent::where('classroom_id', $classroomId)
            ->where('student_email', Auth::user()->email)
            ->first();

        if (!$classroom) {
            return false;
        }
        $classroom->is_joined = true;
        $classroom->save();

        return true;
    }
}
