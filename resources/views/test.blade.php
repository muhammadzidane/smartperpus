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
        .books {
            display: flex;
            flex-wrap: wrap;
            background-color: pink;
        }

        .book {
            width: 175px;
            height: 335px;
            border: 2px solid black;
            word-break: break-all;
        }

        .book:not(:last-child) {
            margin-right: 1rem;
        }

        .book-image {
            background-color: red;
            height: 60%;
        }

        .book-image img {
            width: auto;
            height: 100%;
            object-fit: contain;
        }

        .book-description {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: blueviolet;
            height: 40%;
        }

        .book-rating-icon {
            font-size: 10px;
            color: gold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="books">
            <div class="book">
                <div class="book-image">
                    <img class="w-100" src="{{ asset('storage/books/' . 'jujutsu-kaisen-01.jpg') }}" alt="" srcset="">
                </div>
                <div class="book-description px-2">
                    <div class="py-2">
                        <div class="bg-info">{{ Str::limit('Judul buku ', '25', ) }}</div>
                        <div><small>Nama Author</small></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="fa fa-star book-rating-icon" aria-hidden="true"></i>
                                <i class="fa fa-star book-rating-icon" aria-hidden="true"></i>
                                <i class="fa fa-star book-rating-icon" aria-hidden="true"></i>
                                <i class="fa fa-star book-rating-icon" aria-hidden="true"></i>
                                <i class="fa fa-star book-rating-icon" aria-hidden="true"></i>
                                <small>10 Terjual</small>
                            </div>
                            <div>LO</div>
                        </div>
                        <div class="bg-success">Rp20.000</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script ty src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/helper-functions.js') }}"></script>
</body>

</html>
