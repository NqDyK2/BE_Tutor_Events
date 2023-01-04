<?php

namespace App\Http\Services;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventServices
{
    public $storePath = 'public/images/event_images/';
    public $imagePath = 'storage/images/event_images/';

    public function getAllActiveEvents()
    {
        return Event::select(
            'id',
            'name',
            'content',
            'image',
            'type',
            'location',
            'start_time',
            'end_time',
            'trashed_at'
        )
        ->withCount('eventUsers')
        ->withCount([
            'eventUsers as registered' => function ($q) {
                return $q->where('user_email', Auth::user()->email);
            }
        ])
        ->where('trashed_at', '=', null)
        ->orderBy('start_time', 'desc')
        ->get();
    }

    public function getTrashedEvents()
    {
        return Event::select(
            'id',
            'name',
            'content',
            'image',
            'type',
            'location',
            'start_time',
            'end_time',
            'trashed_at'
        )
        ->withCount('eventUsers')
        ->where('trashed_at', '!=', null)
        ->orderBy('start_time', 'desc')
        ->get();
    }

    public function create($request)
    {
        $data = $request->input();

        $imageName = $request->file('image')->hashName();
        $data['image'] = $this->imagePath . $imageName;

        $request->image->storeAs($this->storePath, $imageName);

        return Event::create($data);
    }

    public function update($request, $event)
    {
        $data = $request->input();

        if ($request->file('image')) {
            $imageName = $request->file('image')->hashName();
            $data['image'] = $this->imagePath . $imageName;

            $request->image->storeAs($this->storePath, $imageName);
            Storage::delete(str_replace("storage", "public", $event->image));
        }

        return $event->update($data);
    }

    public function trashingEvent($event)
    {
        $event->update([
            "trashed_at" => now()
        ]);

        return response([
            'message' => 'Sự kiện đã được chuyển vào thùng rác',
        ], 200);
    }

    public function restoreEvent($event)
    {
        $event->update([
            "trashed_at" => null
        ]);

        return response([
            'message' => 'Khôi phục sự kiện thành công',
        ], 200);
    }

    public function getUpcomingEvent()
    {
        $user = Auth::user();
        $eventUser = $user->eventUsers()->whereHas('event', function ($q)
        {
            return $q->where('start_time', '>', now())
                ->where('start_time', '<', now()->addDays(2)->endOfDay());
        })->first();
        return $eventUser->event;
    }
}
