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
    <div class="register-user container-lg py-4">
        <div class="form-register">
            <form id="form-register" action="{{ route('register') }}" method="POST">
                <div class="text-right p-0"><a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left text-body"></i></a></div>
                <div class="mb-4">
                    @if (Route::currentRouteName() == 'user.create')
                        <h5 class="tred-bold">Tambah User</h5>
                    @else
                        <h5 class="tred-bold">Buat Akun Anda Sekarang</h5>
                        <div class="mt-4 pb-2">Sudah Memiliki akun? <a href="{{ route('login') }}" class="text-decoration-none tred-bold">Masuk</a></div>
                    @endif
                </div>

                <div id="error-register"></div>

                <div class="form-group">
                    <label for="nama_awal">Nama Awal</label>
                    <input type="text" name="nama_awal" id="nama_awal" class="form-control-custom register-form" required>
                    @error('nama_awal')
                    <span class="tred small small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nama_akhir">Nama Akhir</label>
                    <input type="text" name="nama_akhir" id="nama_akhir" class="form-control-custom register-form" required>
                    @error('nama_akhir')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @can('viewAny', \App\Models\User::class)
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control-custom register-form" name="role" id="role">
                            <option value="guest">Guest</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                @endcan
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control-custom register-form" required>
                    @error('email')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control-custom register-form" autocomplete="off" required>
                    @error('password')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password</label>
                    <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control-custom register-form" autocomplete="off" required>
                    @error('konfirmasi_password')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <button id="button-register" class="button-submit" type="submit">Daftar</button>
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
    <script src="{{ asset('js/helper-functions.js') }}"></script>
</body>
</html>
