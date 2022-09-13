<?php

namespace App\Services;

use App\Models\Issue;

class IssueServices
{

    public function getAll()
    {
        return Issue::paginate(DEFAULT_PAGINATE);
    }

    public function create($data)
    {
        return Issue::create($data);
    }

    public function update($data,$issue)
    {
        return $issue->update($data);
    }

    public function destroy($issue)
    {
        $issue->delete();
        return $issue->trashed();
    }
}