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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom-css.css') }}">

    <style>
        .status-detail {
            overflow: hidden;
            position: relative;
            background-color: pink;
            flex: 1;
            border-right: 1px solid black;
            z-index: 1;
        }

        .status-detail-circle {
            width: 40px;
            height: 40px;
            border-radius: 100%;
            background-color: green;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        .status-detail::after,
        .status-detail::before {
            position: absolute;
            z-index: -1;
            top: 19px;
            content: '';
            display: inline-block;
            width: 100%;
            height: 3px;
            background-color: red;
        }

        .status-detail:first-child::after,
        .status-detail:first-child::before {
            width: 50%;
            right: 0;
        }

        .status-detail:last-child::after,
        .status-detail:last-child::before {
            width: 50%;
        }
    </style>
</head>

<body>
    <div class="d-flex w-50 bg-info p-3">
        <div class="status-detail">
            <div class="status-detail-circle">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
            </div>
            <div class="mt-2">wkwk</div>
        </div>
        <div class="status-detail">
            <div class="status-detail-circle">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
            </div>
            <div class="mt-2">wkwk</div>
        </div>
        <div class="status-detail">
            <div class="status-detail-circle">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
            </div>
            <div class="mt-2">wkwk</div>
        </div>
        <div class="status-detail">
            <div class="status-detail-circle">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
            </div>
            <div class="mt-2">wkwk</div>
        </div>
        <div class="status-detail">
            <div class="status-detail-circle">
                <i class="fa fa-cart-plus" aria-hidden="true"></i>
            </div>
            <div class="mt-2">wkwk</div>
        </div>
    </div>
</body>

</html>
