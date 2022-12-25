<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Lesson</title>
</head>
<body>
    <b>Hệ thống Tutor FPT Polytechnic thông báo :</b><br>

    <span>Bạn vừa được thêm làm <b>giảng viên</b> lớp phụ đạo <b>{{ $mailData['subject']['name'] }}</b>. Vui lòng truy cập <a href="{{ env('FRONT_END_URL') }}">{{ substr(env('FRONT_END_URL'), 8) }}</a> để quản lý lớp học.</span>
    <br>
    <p>Trân trọng.</p>
</body>
</html>