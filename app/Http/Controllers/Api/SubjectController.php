<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubjectServices;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private $subjectServices;

    public function __construct(SubjectServices $subjectServices){
        $this->subjectServices = $subjectServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Subject = $this->subjectServices->index();
        return response([
            '$Subject' => $Subject
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Subject = $this->subjectServices->store($request->input());
        return response([
            'status' => true,
            'message' => 'show Subject successfully',
            'data' => $Subject
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = $this->subjectServices->show($id);
        return response([
            'status' => true,
            'data' => $subject
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subject = $this->subjectServices->update($request->input(), $id);
        if ($subject) {
            return response([
                'message' => 'update Subject successfully',
                'status' => true
            ],200);
        } else {
            return response([
                'message' => 'update Subject failed',
                'status' => false
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
        if (!$id) {
            return response([
                'message' => 'Subject not exist',
                'status' => false
            ],400);
        }

        $checkDeleteSubject = $this->subjectServices->destroy($id);

        if ($checkDeleteSubject) {
            return response([
                'message' => 'delete Subject successfully',
                'status' => true
            ],200);
        } else {
            return response([
                'message' => 'delete Subject failed',
                'status' => false
            ],400);
        }
    }
}
