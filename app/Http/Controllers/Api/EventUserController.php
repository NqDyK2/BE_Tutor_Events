<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\StoreFeedbackEventRequest;
use App\Services\EventUserServices;
use Illuminate\Http\Request;

class EventUserController extends Controller
{
    protected $eventUserServices;
    public function __construct(EventUserServices $eventUserServices){
        $this->eventUserServices = $eventUserServices;
    }

    public function create(Request $request)
    {
        $response = $this->eventUserServices->create($request->input());

        return $response;
    }

    public function destroy(Request $request)
    {
        $response = $this->eventUserServices->destroy($request->input());

        return $response;
    }

    public function storeFeedback(StoreFeedbackEventRequest $request)
    {
        $event = $request->get('event');

        $response = $this->eventUserServices->storeFeedback($request->input(), $event);

        return $response;
    }
}
