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
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom-css.css') }}">
</head>

<body>
    <div class="center">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-5">
                    <div>
                        <form id="form-register" action="{{ route('register') }}" method="POST">
                            <div class="mb-4">
                                <h5 class="tred-bold">Buat Akun Anda Sekarang</h5>
                                <div class="mt-4">Sudah Memiliki akun? <a href="{{ route('login') }}" class="text-decoration-none tred-bold">Masuk</a></div>
                            </div>

                            @isset($errors)
                                @if ($errors->any())
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                    <div>
                                        <strong>{{ $error }}</strong>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            @endisset

                            <div id="error-register"></div>

                            <div class="form-group">
                                <label for="nama_awal">Nama Awal</label>
                                <input type="text" name="nama_awal" id="nama_awal" class="form-control-custom register-form" value="{{ old('nama_awal') }}">
                            </div>
                            <div class="form-group">
                                <label for="nama_akhir">Nama Akhir</label>
                                <input type="text" name="nama_akhir" id="nama_akhir" class="form-control-custom register-form" value="{{ old('nama_akhir') }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control-custom register-form" value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control-custom register-form" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="konfirmasi_password">Konfirmasi Password</label>
                                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control-custom register-form" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <button class="cursor-disabled btn btn-outline-danger w-100" type="submit" disabled>Daftar</button>
                            </div>
                            <div>
                                Dengan mendaftarkan akun, anda menyetujui <span class="tred">Syarat & Ketentuan</span> dan
                                <span class="tred">Kebijakan Privasi</span> Dari Smartperpus.
                            </div>
                            @csrf
                        </form>
                    </div>
                </div>
                <div class="col-md-7 d-flex justify-content-center align-items-center">
                    <div class="w-100 d-none d-md-block">
                        <img class="w-100" src="{{ asset('img/form-register.jpg') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/helper-functions.js') }}"></script>
    <script src="{{ asset('js/register.js') }}"></script>
</body>

</html>
