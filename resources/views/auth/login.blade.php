<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smartperpus</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom-css.css') }}">
</head>

<body>
    <div class="register-user container py-4">
        <div class="form-register">
            <form id="form-login" action="{{ route('login') }}" method="POST">
                <h5 class="tred-bold mb-4">Login</h5>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control-custom">
                </div>
                <div class="form-group position-relative">
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control-custom" aria-describedby="helpId" autocomplete="off">
                    </div>
                    <button id="toggle-password" type="button" class="show-password user-login">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="form-group">
                    <button id="button-login" class="button-submit" type="submit">Login</button>
                </div>
                <div class="mb-3">
                    <div class="login-atau tred">Atau login dengan</div>
                </div>
                <div class="form-group">
                    <div class="another-login text-center">
                        <span class="p-3"><a href="#"><i class="fab fa-facebook-f login-hover"></i></a></span>
                        <span class="dot-login"></span>
                        <span class="p-3"><a href="#"><i class="fab fa-google login-hover"></i></a></span>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h6 class="text-grey">Belum Memiliki Akun ?</h6>
                    <a href="{{ route('register') }}" class="text-decoration-none tred-bold">Daftar Sekarang</a>
                </div>
                @csrf
            </form>
        </div>
        <div class="register-user-pict">
            <img src="{{ asset('img/form-register.jpg') }}">
        </div>
    </div>

    <div class="text-center my-5">
        <h6 class="tred-bold">Smartperpus - {{ date('Y', strtotime('now')) }}</h6>
    </div>

    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/helper-functions.js') }}"></script>
</body>

</html>
