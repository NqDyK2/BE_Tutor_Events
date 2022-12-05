<?php
namespace App\Services;

use App\Models\EventFeedback;
use App\Models\EventUser;
use Illuminate\Support\Facades\Auth;

Class EventUserServices
{
    public function create($event)
    {
        $user = Auth::user();

        $eventUser = EventUser::where('user_email', $user->email)->where('event_id', $event->id)->exists();

        if ($eventUser) {

            return response([
                'message' => 'Bạn đã đăng ký tham gia sự kiện này rồi',
            ], 400);

        }

        $data= [
            'user_email' => $user->email,
            'event_id' => $event->id
        ];

        EventUser::create($data);

        return response([
            'message' => 'Đăng ký tham gia sự kiện thành công'
        ], 201);
    }

    public function destroy($event)
    {
        $user = Auth::user();

        $eventUser = EventUser::where('user_email', $user->email)->where('event_id', $event->id)->first();

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

    public function storeFeedback($data, $event)
    {
        $user = Auth::user();

        $eventUser = EventUser::where('user_email', $user->email)->where('event_id', $event->id)->exists();

        if (!$eventUser) {
            return response([
                'message' => 'Bạn chưa tham gia sự kiện này nên bạn không thể thực hiện đánh giá',
            ], 400);
        }

        $checkIssetFeedback = EventFeedback::where('user_id', $user->id)->where('event_id', $event->id)->exists();
        
        if ($checkIssetFeedback) {
            return response([
                'message' => 'Bạn đã đánh giá sự kiện này',
            ], 400);
        }

        $presentTime = now();

        if ($event->start_time > $presentTime) {
            return response([
                'message' => 'Sự kiện này chưa diễn ra nên bạn chưa thể đánh giá',
            ], 400);
        }
        elseif($event->start_time <= $presentTime && $presentTime <= $event->end_time) {
            return response([
                'message' => 'Sự kiện này đang diễn ra nên bạn không thể đánh giá được',
            ], 400);
        }

        $data['user_id'] =  $user->id;
        $data['event_id'] =  $event->id;

        EventFeedback::create($data);

        return response([
            'message' => 'Đánh giá sự kiện thành công'
        ], 201);
    }
}