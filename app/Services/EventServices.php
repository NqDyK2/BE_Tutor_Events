<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventServices
{
    public function index(){
        return Event::select(
            'events.id',
            'events.name',
            'events.content',
            'events.image',
            'events.type',
            'events.location',
            'events.start_time',
            'events.end_time'
        )
        ->leftJoin('event_users', 'events.id', '=', 'event_users.event_id')
        ->groupBy(
            'tutors.events.id',
            'tutors.events.name', 
            'tutors.events.content',
            'tutors.events.image',
            'tutors.events.type',
            'tutors.events.location',
            'tutors.events.start_time', 
            'tutors.events.end_time'
        )
        ->withCount('eventUsers')
        ->orderBy('events.start_time', 'desc')
        ->get();
    }

    public function create($data)
    {
        $imageName = date('Ymd').'_'.date('His').'_'.$data->file('image')->getClientOriginalName();
        $data->image->storeAs('images/event_images', $imageName);
        $urlImage = 'images/event_images/'.$imageName;
        $data = [
            'name' => $data->name,
            'content' => $data->content,
            'type' => $data->type,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time,
            'image' => $urlImage,
            'location' => $data->location
        ];
        return Event::create($data);
    }

    public function update($data, $event)
    {
        if($data->hasFile('image')){
            Storage::delete($event->image);
        };
        $dataEdit = [];
        foreach ($data->all() as $key => $value) {
            if ($value != null) {
                $dataEdit[$key] = $value;
            }
        }
        if (isset($dataEdit['image']) && $dataEdit['image'] != null) {
            $imageName = date('Ymd').'_'.date('His').'_'.$dataEdit['image']->getClientOriginalName();
            $data->image->storeAs('images/event_images', $imageName);
            $urlImage = 'images/event_images/'.$imageName;
            $dataEdit['image'] = $urlImage; 
        }
        return $event->update($dataEdit);
    }

    public function destroy($event)
    {
        $eventUsers = EventUser::where('event_id', $event->id)->exists();
        if ($eventUsers) {
            return response([
                'message' => 'Đã có tài khoản đăng ký tham gia sự kiện này, bạn không thể xóa sự kiện',
            ], 400);
        }
        if ($event->image != null) {
            Storage::delete($event->image);
        }
        $event->delete();
        return response([
            'message' => 'Xóa sự kiện thành công',
        ], 200);
    }
}