<?php

namespace App\Services;

use App\Models\Semester;

class SemesterServices
{
    public function getAll()
    {
        return Semester::paginate(DEFAULT_PAGINATE);
    }

    public function create($data)
    {
        return Semester::create($data);
    }

    public function update($data,$Semester)
    {
        return $Semester->update($data);
    }

    public function destroy($id)
    {
        $Semester = Semester::find($id);
        $Semester->delete();
        return $Semester->trashed();
    }
}