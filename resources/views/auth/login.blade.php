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
            vertical-align: middle;
            justify-content: center;
            text-align: center;
        };
        h1{
            margin: 0 auto;
        }
        a{
            color: orange;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><a href="{{ route('getUrl') }}">Login With Google</a></h1>
        <p>Token for dev</p>
        @foreach ($tokens as $x)
            <a href="http://localhost:3000/checkpoint?token={{$x->token}}">{{$x->desc}}</a>
        @endforeach
    </div>
</body>
</html>