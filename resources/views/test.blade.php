<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smartperpus</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .book {
            background-color: red;
            width: 100%;
        }

        .boxes {
            background-color: hotpink;
            display: grid;
            gap: 25px;
            grid-template-columns: repeat(auto-fit, 160px);
            justify-content: center;
        }

        .box {
            background: red;
            display: block;
            height: 160px;
        }

        .test {
            background-color: red;
        }
    </style>
</head>

<body>
    <div class="container bg-info">
        <div class="boxes">
            <div class="box">1</div>
            <div class="box">1</div>
            <div class="box">1</div>
            <div class="box">1</div>
            <div class="box">1</div>
            <div class="box">1</div>
        </div>
    </div>
</body>

</html>
