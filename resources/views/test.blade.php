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
    <link rel="stylesheet" href="{{ asset('css/custom-css.css') }}">
    <style>
    .parent {
        background-color :aquamarine;
        width: 400px;
        height: 500px;
        margin: 0 auto;
        padding-top: 7px;
    }

    img {
        width: 60%;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    </style>
</head>
<body>
    <div class="parent">
        <img src="{{ asset('img/books_test_image/haikyuu-22.jpg') }}" alt="" srcset="">
    </div>
</body>
</html>
