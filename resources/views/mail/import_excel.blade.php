<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Lesson</title>
</head>
<body>
    <p>Bạn vừa được giáo viên {{$content['teacher']}} thêm lớp phụ đạo, hãy truy cập vào <a href="https://tutor.fpoly.tech/">https://tutor.fpoly.tech/</a> để theo dõi lịch học</p>
    {{-- <table border="1">
        <thead>
            <tr>
                <th colspan="5">Chi tiết lớp học</th>
            </tr>
            <tr>
                <th>Kì học</th>
                <th>Tên chuyên ngành</th>
                <th>Tên môn học</th>
                <th>Mã môn học</th>
                <th>Thời gian kì học</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$content['name_semester']}}</td>
                <td>{{$content['name_major']}}</td>
                <td>{{$content['name_subject']}}</td>
                <td>{{$content['code_subject']}}</td>
                <td>{{$content['start_time_semester']}} đến {{$content['end_time_semester']}}</td>
            </tr>
        </tbody>
    </table> --}}
</body>
</html>