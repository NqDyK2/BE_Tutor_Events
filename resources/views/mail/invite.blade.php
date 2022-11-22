<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <b>Hệ thống Tutor FPT Polytechnic thông báo :</b><br>
    
    <p>Lớp học phụ đạo <b>{{ $mailData['subject']['name']}}</b> đang diễn ra. Hiện tại bạn đang vắng mặt buổi học này.</p>

    <ul>
        <li>Môn học: {{ $mailData['subject']['name']}}</li>
        <li>Thời gian: {{  substr($mailData['lesson']['start_time'], 11, -3) }} đến {{  substr($mailData['lesson']['end_time'], 11, -3) }} ngày {{  date_format(date_create($mailData['lesson']['start_time']), "d/m/Y") }}</li>
        <li>
            Phòng học:
            @if ($mailData['lesson']['type'] == 0)
                <a href="{{ $mailData['lesson']['class_location'] }}">{{ $mailData['lesson']['class_location'] }}</a>
            @else
                {{ $mailData['lesson']['class_location'] }}
            @endif
        </li>
    </ul>

    <span>Vui lòng truy cập <a href="{{ env('FRONT_END_URL') }}">{{ substr(env('FRONT_END_URL'), 8) }}</a> để theo dõi lịch học.</span>
    <br>
    <p>Trân trọng.</p>
</body>
</html>