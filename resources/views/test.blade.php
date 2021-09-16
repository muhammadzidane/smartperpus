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
        .status-modal-detail {
            position: relative;
            overflow: hidden;
            z-index: 1;
            flex: 1;
        }

        .status-modal-detail-circle {
            width: 40px;
            height: 40px;
            border-radius: 100%;
            background-color: #e2d5d5;
            border: 3px solid #6E7C7C;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            box-shadow: 0 0 3px #6E7C7C;
        }

        .status-modal-detail-circle i {
            font-size: 15px;
            color: #29BB89;
        }

        .status-modal-detail::after,
        .status-modal-detail::before {
            position: absolute;
            z-index: -1;
            top: 19px;
            content: '';
            display: inline-block;
            width: 100%;
            height: 3px;
            background-color: #6E7C7C;
            box-shadow: 0 0 1px #6E7C7C;
        }

        .status-modal-detail:first-child::after,
        .status-modal-detail:first-child::before {
            width: 50%;
            right: 0;
        }

        .status-modal-detail:last-child::after,
        .status-modal-detail:last-child::before {
            width: 50%;
        }

        .status-modal-detail-active {
            background-color: #2C5D63;
        }
    </style>
</head>

<body>
    <span class="d-inline-block" data-toggle="popover" data-content="Disabled popover">
        <button class="btn btn-primary" style="pointer-events: none;" type="button" disabled>Disabled button</button>
    </span>
</body>

</html>
