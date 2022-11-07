<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Lesson</title>
    <link rel="stylesheet" href="{{ asset('mail.css') }}">
</head>
<body>
    <h2>Bạn vừa vừa được giáo viên {{$content['teacher']}} thêm lớp phụ đạo</h2>
    <table class="container">
        <thead>
            <tr>
                <th></th>
                <th><h1>Kì học</h1></th>
                <th><h1>Tên chuyên ngành</h1></th>
                <th><h1>Tên môn học</h1></th>
                <th><h1>Mã môn học</h1></th>
                <th><h1>Thời gian kì học</h1></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Chi tiết lớp học</td>
                <td>{{$content['name_semester']}}</td>
                <td>{{$content['name_major']}}</td>
                <td>{{$content['name_subject']}}</td>
                <td>{{$content['code_subject']}}</td>
                <td>{{$content['start_time_semester']}} đến {{$content['end_time_semester']}}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>