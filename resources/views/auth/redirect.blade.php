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
            background: orange;
            border-radius: 10px;
        }
        a:hover{
            background: rgb(0, 0, 0);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $token }}</h1>
        <b>
            <a href="http://localhost:3000/checkpoint?token={{$token}}">Login Localhost</a>
            <a href="https://tutor-event-poly-dev.vercel.app/checkpoint?token={{$token}}">Login vercel</a>
        </b>
    </div>
</body>
</html>