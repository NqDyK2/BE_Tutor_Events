<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Lesson</title>
</head>
<body>
    <b>Hệ thống Tutor FPT Polytechnic thông báo :</b>
    <br>
    
    <p>Sinh viên {{$mailData['student']['name']}} ({{$mailData['student']['code']}}) vừa được thêm vào lớp Phụ đạo <b>{{$mailData['subject']['name']}}</b>.</p>

    <span>Vui lòng truy cập <a href="{{ env('FRONT_END_URL') }}">{{ env('FRONT_END_URL') }}</a> để theo dõi lịch học.</span>
    <br>
    <p>Trân trọng.</p>
</body>
</html>