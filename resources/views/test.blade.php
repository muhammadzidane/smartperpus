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

    <!-- Styles -->

    <style>
        .books {
            background-color: pink;
            /* max-width: 100%; */
            display: flex;
            flex-wrap: wrap;
        }

        .book-image {
            border: 2px solid red;
            width: 160px;
            height: auto;
            padding: 5px 8px;
            overflow: hidden;
        }

        .book-image img {
            object-fit: contain;
            height: 150px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="books">
        <div class="book">
            <div class="book-image">
                <img src="{{ asset('img/no-data.png') }}" alt="">
            </div>
            <div class="book-info">
                <div>dqmwlkmdwqlkmwqlkwqd</div>
            </div>
        </div>
        <div class="book">
            <div class="book-image">
                <img src="{{ asset('img/books_test_image/haikyuu-21.jpg') }}" alt="">
            </div>
            <div class="book-info">
                <div>dqmwlkmdwqlkmwqlkwqd</div>
            </div>
        </div>

    </div>
</body>

</html>
