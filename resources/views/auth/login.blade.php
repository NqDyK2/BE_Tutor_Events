<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            height: 100vh;
            width: 100vw;
            display: table-cell;
            justify-content: center;
            text-align: center;
        };
        h1{
            margin: 0 auto;
        }
        a{
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            background: rgb(54, 61, 85);
            border-radius: 10px;
        }
        a:hover{
            background: rgb(0, 0, 0);
        }
    </style>
</head>
<body>
    <div class="container">
        <h3><a href="{{ route('getUrl') }}">Login With your Google account</a></h3>
        <p>Danh sách token:</p>
        <b>
            @foreach ($tokens as $x)
            <span>{{$x->desc}}: {{$x->token}} </span>
            <a href="http://localhost:3000/checkpoint?token={{$x->token}}">Login</a>
            @endforeach
        </b>
    </div>
</body>
</html>