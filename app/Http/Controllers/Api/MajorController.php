<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Major\CreateMajorRequest;
use App\Http\Requests\Major\UpdateMajorRequests;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Services\MajorServices;
use GuzzleHttp\Promise\Create;

class MajorController extends Controller
{
    private $majorServices;

    public function __construct(MajorServices $majorServices)
    {
        $this->majorServices = $majorServices;
    }

    public function index()
    {
        $majors = $this->majorServices->getAll();

        return response([
            'major' => $majors
        ],200);
    }

    public function store(CreateMajorRequest $request)
    {
        $majors = $this->majorServices->create($request->input());

        return response([
            'status' => true,
            'massage' => 'Major created successfully',
            'data' => $majors
        ],201);
    }

    public function show(Request $request)
    {
        $majorShow = $request->get('major');
        return response([
            'status' => true,
            'data' => $majorShow
        ],200);
    }

    public function update(UpdateMajorRequests $request)
    {
        $major = $request->get('major');

        $majors = $this->majorServices->update($request->input() , $major);

        if ($majors){
            return response([
                'status' => true,
                'massage' => 'Major update successfully',
                'data' => $majors
            ],201);
        }else{
            return response([
                'status' => false,
                'massage' => 'update Subject failed'
            ],400);
        }
    }

    public function destroy(Request $request)
    {
        $major = $request->get('major');
        $majorDelete = $this->majorServices->destroy($major);

        if($majorDelete){
            return response([
                'status' => true,
                'message' => 'Delete Major successfully',
            ],200);
        } else {
            return response([
                'status' => false,
                'massage' => 'Delete Major false'
            ],400);
        }
    }
}
