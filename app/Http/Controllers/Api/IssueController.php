<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Issue\CreateIssueRequest;
use App\Http\Requests\Issue\UpdateIssueRequest;
use App\Models\Issue;
use App\Services\IssueServices;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    private $issueServices;

    public function __construct(IssueServices $issueServices)
    {
        $this->issueServices = $issueServices;
    }

    public function index()
    {
        $issue = $this->issueServices->getAll();
        
        return response([
            'issue' => $issue
        ],200);
    }

    public function store(CreateIssueRequest $request)
    {
        $issue = $this->issueServices->create($request->input());

        return response([
            'status' => true,
            'message' => 'Issue created successfully',
            'data' => $issue
        ],201);
    }

    public function show(Request $request)
    {
        $issue = $request->get('issue');

        return response([
            'status' => true,
            'data' => $issue
        ],200);
    }

    public function update(UpdateIssueRequest $request)
    {
        $issue = $request->get('issue');
        $issueUpdate = $this->issueServices->update($request->input(),$issue);
        if($issueUpdate)
        {
            return response([
                'status' => true,
                'message' => 'Issue Update Successfully',
            ],201);
        }else {
            return response([
                'status' => false,
                'message' => 'Update Issue False'
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        $issue = $request->get('issue');
        $issueDestroy = $this->issueServices->destroy($issue);

        if($issueDestroy){
            return response([
                'status' => true,
                'message' => 'Issue Delete Successfully'
            ]);
        }else {
            return response([
                'status' => false,
                'message' => 'Delete false'
            ]);
        }
    }
}
