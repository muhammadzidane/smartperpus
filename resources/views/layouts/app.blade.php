<!doctype html>
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
                        <form class="search-form" action="#" method="post">
                            <button type="submit">
                                <i class="fas fa-search search-icon"></i>
                            </button>
                            <input type="text" name="search" id="search" class="search-text"
                            placeholder="Judul Buku, Nama Author">
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
                            <button id="login" class="btn btn-red" data-toggle="modal" data-target="#modelId">Masuk</button>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
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
                                <div>
                                    <div class="p-3 ">
                                        <h6 class="dropdown-item"><a class="text-body text-decoration-none" href="#">Akun Saya</a></h6>
                                        <h6 class="dropdown-item"><a class="text-body text-decoration-none" href="#">Keranjang Saya</a></h6>
                                        <h6 class="dropdown-item"><a class="text-body text-decoration-none" href="#">Wishlist Saya</a></h6>
                                    </div>
                                    <a class="dropdown-item text-right" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </div>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>

        <!-- Modal Login -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered p-5" role="document">
                <div class="modal-content modal-content-login">
                    <div class="px-3 d-flex justify-content-between">
                        <h5 class="modal-title tred login-header">Login</h5>
                        <button id="login-exit" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (session('errorLogin'))
                            <div class="tred small small mb-2" role="alert">
                                <strong>{{ session('errorLogin') }}</strong>
                            </div>
                        @endif
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control-login"
                                  aria-describedby="helpId" value="{{ old('email') }}">
                                @error('email')
                                    <span class="tred small small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="d-flex">
                                    <input type="password" name="password" id="password" class="form-control-login"
                                      aria-describedby="helpId" autocomplete="off">
                                    <button id="toggle-password" type="button" class="show-password">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                    @error('password')
                                        <span class="tred small small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                <div>
                                    <div class="text-right">
                                        <a href="#"><small>Lupa Kata Sandi ?</small></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button id="button-login" type="submit">Login</button>
                            </div>
                            <div class="mb-3">
                                <div class="login-atau">atau</div>
                            </div>
                            <div class="form-group">
                                <div class="another-login text-center">
                                    <span class="p-3"><a href="#"><i class="fab fa-facebook-f"></i></a></span>
                                    <span class="p-3"><a href="#"><i class="fab fa-google"></i></a></span>
                                </div>
                            </div>
                        </form>

                        <div class="text-center mt-5">
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

</html>
