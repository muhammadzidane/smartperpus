<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

    <script src="{{ asset('js/app.js') }}"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .slider {
            display: flex;
            padding: 10px 0;
            /* overflow: hidden; */
        }

        .slider:hover {
            transition: transform 500ms;
            transform: translate(-20px);
        }

        .book {
            display: flex;
            padding: 4px 5px;
            width: 70px;
            margin-right: 0.75rem;
            border: 2px solid black;
            flex-shrink: 0;
        }

        .book img {
            margin: auto;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="bg-info">
        <div class="w-25 bg-success">
            <div class="slider">
                <div class="book">
                    <img src="{{ asset('img/books_test_image/haikyuu-21.jpg') }}">
                </div>
                <div class="book">
                    <img src="{{ asset('img/form-register.jpg') }}">
                </div>
                <div class="book">
                    <img src="{{ asset('img/form-register.jpg') }}">
                </div>
                <div class="book">
                    <img src="{{ asset('img/form-register.jpg') }}">
                </div>
                <div class="book">
                    <img src="{{ asset('img/form-register.jpg') }}">
                </div>
                <div class="book">
                    <img src="{{ asset('img/form-register.jpg') }}">
                </div>
                <div class="book">
                    <img src="{{ asset('img/form-register.jpg') }}">
                </div>
            </div>
        </div>
    </div>

    <script ty src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/helper-functions.js') }}"></script>
</body>

</html>
