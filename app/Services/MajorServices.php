<?php 

namespace App\Services;

use App\Models\Major;

class MajorServices
{
    public function getAll()
    {
        return Major::with('subjects')->paginate(DEFAULT_PAGINATE);
    }

    public function show($id)
    {
        return Major::find($id);
    }

    public function create($data)
    {
        return Major::create($data);
    }

    public function update($data,$major)
    {
        return $major->update($data);
    }

    public function destroy($id)
    {
        $major = Major::find($id);
        $major->delete();
        return $major->trashed();
    }
}