<?php
namespace App\Services;

use App\Models\EventUser;
use Illuminate\Support\Facades\Auth;

Class EventUserServices
{
    public function create($data)
    {
        $data['user_email'] = Auth::user()->email;
        $eventUser = EventUser::where('user_email', $data['user_email'])->where('event_id', $data['event_id'])->first();
        if (isset($eventUser)) {
            return response([
                'message' => 'Bạn đã đăng ký tham gia sự kiện này rồi',
            ], 400);
        }
        EventUser::create($data);
        return response([
            'message' => 'Đăng ký tham gia sự kiện thành công'
        ], 201);
    }

    public function destroy($data)
    {
        $eventUser = EventUser::where('user_email', Auth::user()->email)->where('event_id', $data['event_id'])->first();
        if (isset($eventUser)) {
            $eventUser->delete();
            return response([
                'message' => 'Hủy tham gia sự kiện thành công',
            ], 200);
        }
        return response([
            'message' => 'Bạn chưa tham gia sự kiện này nên không thể hủy tham gia'
        ], 400);
    }
}