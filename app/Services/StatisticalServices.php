<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\ClassStudent;
use App\Models\Semester;

class StatisticalServices
{
    public function getSemesterStatistical($semesterId = null)
    {
        $classroomsStatistical = [];

        $semester = Semester::where('id', $semesterId)
            ->with('classrooms')
            ->first();

        if (!$semester) {
            $semester = Semester::where('semesters.start_time', '<=', now())
                ->where('semesters.end_time', '>=', now())
                ->with('classrooms')
                ->first();
        }
        if (!$semester) {
            $semester = Semester::orderBy('end_time', 'DESC')
                ->with('classrooms')
                ->first();
        }
        if (!$semester) {
            return response([
                'data' => []
            ], 200);
        }

        $semester->statted_lesons_count = 0;
        $semester->total_students_count = 0;
        $semester->warning_students_count = 0;
        $semester->passed_students_count = 0;
        $semester->not_passed_students_count = 0;
        $semester->banned_students_count = 0;
        $semester->joined_students_count = 0;
        $semester->passed_joinned_students_count = 0;
        $semester->not_passed_joinned_students_count = 0;
        $semester->banned_joinned_students_count = 0;
        $semester->teachers = collect([]);
        $semester->tutors = collect([]);

        foreach ($semester->classrooms as $classroom) {
            $data = $this->getClassroomStatistical($classroom->id);
            $classroomsStatistical[] = $data;

            $semester->statted_lesons_count += $data->statted_lesons_count;
            $semester->total_students_count += $data->total_students_count;
            $semester->warning_students_count += $data->warning_students_count;
            $semester->passed_students_count += $data->passed_students_count;
            $semester->not_passed_students_count += $data->not_passed_students_count;
            $semester->banned_students_count += $data->banned_students_count;
            $semester->joined_students_count += $data->joined_students->count();
            $semester->passed_joinned_students_count += $data->passed_joinned_students_count;
            $semester->not_passed_joinned_students_count += $data->not_passed_joinned_students_count;
            $semester->banned_joinned_students_count += $data->banned_joinned_students_count;

            foreach ($data->teachers as $teacher) {
                $teacherInsemester = $semester->teachers->where('email', $teacher->email)->first();

                if (!$teacherInsemester) {
                    $semester->teachers->push($teacher);
                } else {
                    $teacherInsemester->lessons_count += $teacher->lessons_count;
                    $teacherInsemester->working_minutes += $teacher->working_minutes;
                }
            }

            foreach ($data->tutors as $tutor) {
                $tutorInsemester = $semester->tutors->where('email', $tutor->email)->first();

                if (!$tutorInsemester) {
                    $semester->tutors->push($tutor);
                } else {
                    $tutorInsemester->lessons_count += $tutor->lessons_count;
                    $tutorInsemester->working_minutes += $tutor->working_minutes;
                }
            }
        }

        $semester->classrooms_statistical = $classroomsStatistical;

        $semester = $semester->toArray();
        unset($semester['classrooms']);

        return response([
            'data' => $semester
        ], 200);
    }

    function getClassroomStatistical($classroomId)
    {
        $joinedStudents = [];
        $teachers = [];
        $teachersWorkingTimeCount = [];
        $tutors = [];
        $tutorsWorkingTimeCount = [];

        $classroom = Classroom::where('id', $classroomId)
            ->with('subject')
            ->with(
                'lessons',
                function ($q) {
                    return $q->where('attended', 1)
                        ->with('attendances', function ($q) {
                            $q->where('status', true);
                        });
                }
            )
            ->withCount([
                'classStudents as total_students_count',
                'classStudents as warning_students_count' => function ($q) {
                    return $q->where('is_warning', true);
                },
                'classStudents as passed_students_count' => function ($q) {
                    return $q->where('final_result', 1);
                },
                'classStudents as not_passed_students_count' => function ($q) {
                    return $q->where('final_result', 0);
                },
                'classStudents as banned_students_count' => function ($q) {
                    return $q->where('final_result', -1);
                },
            ])
            ->firstOrFail();

        foreach ($classroom->lessons as $lesson) {
            $attendedStudents = $lesson->attendances->pluck('student_email')->toArray();
            $joinedStudents = array_merge($joinedStudents, $attendedStudents);
            $teachers[] = $lesson->teacher_email;

            if ($lesson->tutor_email) {
                $tutors[] = $lesson->tutor_email;
            }
        }

        $joinedStudents = array_unique($joinedStudents);

        $classroom->joined_students = ClassStudent::where('classroom_id', $classroomId)
            ->whereIn('student_email', $joinedStudents)
            ->get();

        $classroom->passed_joinned_students_count = $classroom->joined_students->where('final_result', ClassStudent::FINAL_RESULT_PASSED)->count();
        $classroom->not_passed_joinned_students_count = $classroom->joined_students->where('final_result', ClassStudent::FINAL_RESULT_NOT_PASSED)->count();
        $classroom->banned_joinned_students_count = $classroom->joined_students->where('final_result', ClassStudent::FINAL_RESULT_BANNED)->count();
        $classroom->statted_lesons_count = count($classroom->lessons);

        foreach (array_count_values($teachers) as $key => $value) {
            $workingSeconds = 0;

            foreach ($classroom->lessons->where('teacher_email', $key) as $lesson) {
                $workingSeconds += strtotime($lesson->end_time) - strtotime($lesson->start_time);
            }

            $teachersWorkingTimeCount[] = (object) [
                'email' => $key,
                'lessons_count' => $value,
                'working_minutes' => $workingSeconds / 60
            ];
        }

        foreach (array_count_values($tutors) as $key => $value) {
            $workingSeconds = 0;

            foreach ($classroom->lessons->where('tutor_email', $key) as $lesson) {
                $workingSeconds += strtotime($lesson->end_time) - strtotime($lesson->start_time);
            }

            $tutorsWorkingTimeCount[] = (object) [
                'email' => $key,
                'lessons_count' => $value,
                'working_minutes' => $workingSeconds / 60
            ];
        }

        $classroom->teachers = collect($teachersWorkingTimeCount);
        $classroom->tutors = collect($tutorsWorkingTimeCount);

        return $classroom;
    }
}
