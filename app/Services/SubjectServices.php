<?php

namespace App\Services;

use App\Models\Subject;

class SubjectServices
{
    public function create($data)
    {
        return Subject::create($data);
    }

    public function update($data,$subject)
    {
        return $subject->update($data);
    }

    public function destroy($subject_id)
    {
        $subject = Subject::where('id', $subject_id)
            ->withCount('classrooms')
            ->first();

        if ($subject->classrooms_count != 0) {
            return response([
                'message' => 'Môn học này đã có lớp học, hãy xóa các lớp trước'
            ], 400);
        }

        $subject->delete();

        return response([
            'message' => 'Xóa môn học thành công'
        ], 200);
    }
}
