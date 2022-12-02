<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\EditEventRequest;
use App\Services\EventServices;
class EventController extends Controller
{
    private $eventServices;
    public function __construct(EventServices $eventServices){
        $this->eventServices = $eventServices;
    }

    public function store(CreateEventRequest $request)
    {
        $this->eventServices->create($request);

        return response([
            'message' => 'Tạo sự kiện thành công',
        ], 201);
    }

    public function update(EditEventRequest $request)
    {
        $event = $request->get('event');
        $this->eventServices->update($request, $event);

        return response([
            'message' => 'Cập nhật sự kiện thành công',
        ], 201);
    }
}
