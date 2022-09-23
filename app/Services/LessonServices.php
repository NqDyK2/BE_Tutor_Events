<?php
namespace App\Services;

use App\Models\Lesson;

class LessonServices
{
    public function store($data)
    {
       return Lesson::create($data);
    }

    public function update($data, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return false;
        }
        return $lesson->update($data);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return false;
        }
        $lesson->delete();
        return $lesson->trashed();
    }

}
