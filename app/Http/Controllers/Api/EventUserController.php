<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\StoreFeedbackEventRequest;
use App\Http\Services\EventUserServices;
use Illuminate\Http\Request;

class EventUserController extends Controller
{
    protected $eventUserServices;
    public function __construct(EventUserServices $eventUserServices)
    {
        $this->eventUserServices = $eventUserServices;
    }

    public function create(Request $request)
    {
        $event = $request->get('event');
        $response = $this->eventUserServices->create($event);

        return $response;
    }

    public function destroy(Request $request)
    {
        $event = $request->get('event');
        $response = $this->eventUserServices->destroy($event);

        return $response;
    }

    public function storeFeedback(StoreFeedbackEventRequest $request)
    {
        $event = $request->get('event');
        $response = $this->eventUserServices->storeFeedback($request->input(), $event);

        return $response;
    }
}
