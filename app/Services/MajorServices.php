<?php 

namespace App\Services;

use App\Models\Major;

class MajorServices
{
    public function getAll()
    {
        return Major::paginate( Major::DEFAULT_PAGINATE);
    }

    public function create($data)
    {
        return Major::create($data);
    }

    public function show($id)
    {
        return $major = Major::find($id);
    }
    public function update($data,$id)
    {
        $major = Major::find($id);
        return $major->update($data);
    }

    public function destroy($id)
    {
        $major = Major::find($id);
        $major->delete();
        return $major->trashed();
    }
}