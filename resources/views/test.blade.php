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
    </style>
</head>

<body>

    <div id="user-chat-send-img">
        <div><button id='user-send-img-cancel' class="btn-none mb-3"><i class="fa fa-times"></i></button></div>
        <img id="user-chat-img" src="{{ asset('img/kategori-pilihan/psikologi.jpg') }}">
    </div>
    <div class="d-flex">
        <input class="user-chat-img-information" type="text" name="message" placeholder="Tambah keterangan..." autocomplete="off">
        <button id="user-chat-store-send-img" type="button" class="btn-none">
            <i class="type-message-plane fas fa-paper-plane"></i>
        </button>
    </div>
</body>

</html>
