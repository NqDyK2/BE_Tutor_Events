<?php

namespace App\Services;

use App\Models\Major;

class MajorServices
{
    public function getAll()
    {
        return Major::with('subjects')->get();
    }

    public function create($data)
    {
        return Major::create($data);
    }

    public function update($data, $major)
    {
        return $major->update($data);
    }

    public function destroy($major_id)
    {
        $major = Major::where('id', $major_id)
            ->withCount('subjects')
            ->first();

        if ($major->subject_count != 0) {
            return response([
                'message' => 'Chuyên ngành này đã có môn học, hãy xóa các môn học trong chuyên ngành trước'
            ], 400);
        }

        $major->delete();

        return response([
            'message' => 'Xóa chuyên ngành thành công'
        ], 200);
    }
}
