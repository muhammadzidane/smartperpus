<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-cus-navbar h-100 shadow-sm sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand text-righteous" href="{{ url('/') }}">
                    <img class="logo" src="{{ asset('img/logo.png') }}" alt="">
                    <span class="logo-text">Smartperpus</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->
                    <div class="circle-input">
                        <form class="search-form" action="{{ route('search.books') }}" method="GET">
                            <button type="submit">
                                <i class="fas fa-search search-icon"></i>
                            </button>
                            <input type="text" name="keywords" id="keywords" class="search-text"
                            placeholder="Judul Buku, Nama Author" autocomplete="off">
                            <input type="hidden" name="page" value="1">
                        </form>
                        <div id="search-values">
                            <div>
                                <div class="py-3">
                                    <div>
                                        <h6 class="ml-3 tred-bold">Buku</h6>
                                        <ul id="search-books">
                                        </ul>
                                    </div>
                                    <div>
                                        <h6 class="ml-3 tred-bold">Author</h6>
                                        <ul id="search-authors">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="navbar-nav mr-auto nav-left">
                        <li id="categories" class="nav-item">
                            <a class="nav-link text-body" href="#">Kategori <i class="fas fa-caret-down ml-1"></i></a>
                            <div>
                                <div>
                                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                                        <span class="category">
                                            <a href="#">{{ $category->name }}</a>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-body" href="#">Genre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-body" href="#">Best Seller</i></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-body" href="#">Komik</i></a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item mr-2">
                            <!-- Button trigger login modal -->
                            <button id="login" class="btn btn-red" data-toggle="modal" data-target="#modal-login">Masuk</button>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown position-relative">
                            <a id="navbarDropdown" class="nav-link" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <div class="user">
                                    <div class="user-circle">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-name">
                                        <div>{{ Auth::user()->first_name }}</div>
                                    </div>
                                </div>
                            </a>

                            <div class="dropdown-user dropdown-menu" aria-labelledby="navbarDropdown">
                                <div class="mb-3">
                                    <div>
                                        <a class="dropdown-item" href="#"">Akun Saya</a>
                                    </div>
                                    <div>
                                        <a class="dropdown-item" href="#"">Daftar Wishlist</a>
                                    </div>
                                    <div>
                                        <a class="dropdown-item" href="#">Keranjang Saya</a>
                                    </div>
                                </div>
                                <div>
                                    <form action="{{ route('logout') }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn dropdown-item text-right text-righteous">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('carousel')

        <main class="py-4 container">
            @yield('content')
        </main>

        <!-- Modal Login -->
        <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered p-5" role="document">
                <div class="modal-content modal-content-login">
                    <div class="px-3 mb-4 d-flex justify-content-between">
                        <h5 class="modal-title tred login-header">Login</h5>
                        <button id="login-exit" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (session('errorLogin'))
                            <div class="error-backend" onclick="alertError(`{{ session('errorLogin') }}`)"></div>
                        @endif

                        <div id="error-login">
                        </div>

                        <form id="form-login" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control-custom login-form"
                                 value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="error-backend" onclick="alertError(`Email {{ $message }}`)"></div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="d-flex">
                                    <input type="password" name="password" id="password" class="form-control-custom login-form"
                                     autocomplete="off" required>
                                    <button id="toggle-password" type="button" class="show-password">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="error-backend" onclick="alertError(`Password {{ $message }}`)"></div>
                                @enderror
                                <div>
                                    <div class="text-right">
                                        <a href="{{ route('forgot.password') }}"><small>Lupa Kata Sandi ?</small></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button id="button-submit" class="button-submit" type="submit">Login</button>
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
                        </form>

                        <div class="text-center mt-4">
                            <h6 class="text-grey">Belum Memiliki Akun ?</h6>
                            <a href="{{ route('register') }}" class="text-decoration-none tred-bold">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <footer class="">
            <div class="container">
                <div class="row">
                    Lorem ipsum dolor sit amet consectetur adipisicing elitaaaaaa
                </div>
            </div>
        </footer>
    </div>
</body>


<script src="{{ asset('js/navbar.js') }}"></script>
<script src="{{ asset('js/helper-functions.js') }}"></script>

</html>
