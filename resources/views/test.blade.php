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
    <form action="{{ route('test.post') }}" enctype="multipart/form-data" method="post">
        <input type="file" name="photo" id="photo">
        <button type="submit">Submit</button>
        @csrf
    </form>
    <script src="{{ asset('js/helper-functions.js') }}"></script>
    <script>
        $('form').on('submit', e => {
            e.preventDefault();

            ajaxForm('POST', 'form', '/test', response => {
                console.log(response);
            });
        });
    </script>
</body>
</html>
