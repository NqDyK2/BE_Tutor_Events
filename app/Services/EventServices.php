<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class EventServices
{
    public function create($data)
    {
        $imageName = date('Ymd').'_'.date('His').'_'.$data->file('image')->getClientOriginalName();
        $data->image->storeAs('images/event_images', $imageName);
        $urlImage = 'images/event_images/'.$imageName;
        $data = [
            'name' => $data->name,
            'content' => $data->content,
            'status' => $data->status,
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
            $urlImage = 'images/event_images'.$imageName;
            $dataEdit['image'] = $urlImage; 
        }
        return $event->update($dataEdit);
    }
}