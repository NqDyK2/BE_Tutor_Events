<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\EditEventRequest;
use App\Http\Services\EventServices;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $eventServices;

    public function __construct(EventServices $eventServices)
    {
        $this->eventServices = $eventServices;
    }

    public function index()
    {
        $events = $this->eventServices->getAllActiveEvents();

        return response([
            'data' => $events,
        ], 200);
    }

    public function getTrashedEvents()
    {
        $events = $this->eventServices->getTrashedEvents();

        return response([
            'data' => $events,
        ], 200);
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
        ], 200);
    }

    public function destroy(Request $request)
    {
        $event = $request->get('event');
        $response = $this->eventServices->trashingEvent($event);

        return $response;
    }

    public function restore(Request $request)
    {
        $event = $request->get('event');
        $response = $this->eventServices->restoreEvent($event);

        return $response;
    }
}
