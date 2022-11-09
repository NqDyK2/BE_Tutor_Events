<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Major\CreateMajorRequest;
use App\Http\Requests\Major\UpdateMajorRequests;
use Illuminate\Http\Request;
use App\Services\MajorServices;

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
            'data' => $majors
        ], 200);
    }

    public function store(CreateMajorRequest $request)
    {
        $this->majorServices->create($request->input());

        return response([
            'message' => 'Tạo chuyên ngành thành công',
        ], 201);
    }

    public function update(UpdateMajorRequests $request)
    {
        $major = $request->get('major');

        $this->majorServices->update($request->input(), $major);

        return response([
            'message' => 'Cập nhật chuyên ngành thành công',
        ], 201);
    }

    public function destroy(Request $request)
    {
        $response = $this->majorServices->destroy($request->major_id);

        return $response;
    }
}
