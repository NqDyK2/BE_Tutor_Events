<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Hiện tại bạn đang vắng mặt buổi học này, mời bạn tham buổi học <br><br>
        Giảng viên: {{$content['teacher_email']}} <br><br>
        Trợ giảng: {{$content['tutor_email']}}</h2>
    <table border="1">
        <thead>
            <tr>
                <th></th>
                <th>Nội dung</th>
                <th>Hình thức</th>
                <th>Thời gian</th>
                <th>Phòng học</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Chi tiết thời gian buổi học</td>
                <td>{{$content['content']}}</td>
                <td>{{$content['type'] === 0 ? 'Online' : 'Offline'}}</td>
                <td>{{$content['start_time']}} đến {{$content['end_time']}}</td>
                <td>@if ($content['type'] === 0)
                    <a href="{{$content['class_location']}}">Tại đây</a>
                @else
                    {{$content['class_location']}}
                @endif</td>
            </tr>
        </tbody>
    </table>
</body>
</html>