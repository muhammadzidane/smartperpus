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
        .my-navbar {
            background-color: #fff600;
            font-family: 'Righteous', cursive;
            padding: 8px 0;
        }

        .center {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-grip-line {
            margin-left: auto;
            display: none;
        }

        .search-icon,
        .responsive-search-icon {
            position: absolute;
            background-color: #ea5455;
            display: flex;
            width: 45px;
            height: 45px;
            border-radius: 100%;
            color: white;
            font-size: 18px;
        }

        .search-icon {
            left: -25px;
        }

        .responsive-search-icon {
            left: 0;
        }

        .responsive-search-text {
            width: 100%;
            box-sizing: border-box;
            border: 2px solid #ea5455;
            height: 45px;
            text-indent: 53px;
            border-top-right-radius: 23px;
            border-bottom-right-radius: 23px;
            border-top-left-radius: 23px;
            border-bottom-left-radius: 23px;
            outline: none;
        }

        @media screen and (max-width: 992px) {

            .navbar-content {
                display: none;
            }

            .navbar-grip-line {
                display: block;
            }
        }

        .books {
            background-color: pink;
            display: flex;
            flex-wrap: wrap;
        }

        .book-1 {
            margin: auto;
            width: 160px;
            border: 1px solid red;
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="books">
            <div class="book-1">book 1</div>
            <div class="book-1">book 2</div>
            <div class="book-1">book 3</div>
            <div class="book-1">book 4</div>
            <div class="book-1">book 5</div>
            <div class="book-1">book 6</div>
        </div>
    </div>
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/helper-functions.js') }}"></script>
</body>

</html>
