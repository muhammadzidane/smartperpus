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
            <form action="{{ route('register') }}" method="POST">
                <div class="mb-4">
                    <h5 class="tred-bold">Buat Akun Anda Sekarang</h5>
                    <div class="pb-2">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-decoration-none tred-bold">Masuk</a></div>
                </div>
                <div class="form-group">
                    <label for="first_name">Nama Awal</label>
                    <input type="text" name="first_name" id="first_name" class="form-control-login  ">
                    @error('first_name')
                    <span class="tred small small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="last_name">Nama Akhir</label>
                    <input type="text" name="last_name" id="last_name" class="form-control-login">
                    @error('last_name')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control-login">
                    @error('email')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control-login">
                    @error('password')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="re_password">Ulangi Password</label>
                    <input type="text" name="re_password" id="re_password" class="form-control-login">
                    @error('re_password')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <button id="button-login" type="submit">Daftar</button>
                </div>
                <div>
                    Dengan mendaftarkan akun, anda menyetujui <span class="tred">Syarat & Ketentuan</span> dan
                    <span class="tred">Kebijakan Privasi</span> Dari Smartperpus.
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
</body>
</html>

