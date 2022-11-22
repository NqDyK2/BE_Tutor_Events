<?php

namespace App\Services;

use App\Models\Classroom;
use App\Models\Semester;

class SemesterServices
{
    public function getAll()
    {
        return Semester::select(
            'id',
            'name',
            'start_time',
            'end_time'
        )
        ->orderBy('start_time', 'desc')
        ->get();
    }

    public function create($data)
    {
        return Semester::create($data);
    }

    public function update($data,$Semester)
    {
        return $Semester->update($data);
    }

    public function destroy($semesterId)
    {
        $extended = Classroom::where('semester_id', $semesterId)
        ->whereHas('lessons', function ($q) {
            $q->where('attended', true);
        })->exists();

        if ($extended) {
            return response([
                'message' => 'Kỳ học đã diễn ra, không thể xóa kỳ học này'
            ], 400);
        }

        Semester::findOrFail($semesterId)->delete();

        return response([
            'message' => 'Xóa kỳ học thành công'
        ], 200);
    }
}
