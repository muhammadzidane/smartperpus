<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smartperpus</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

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
                        <form class="search-form" action="" method="post">
                            <button type="submit">
                                <i class="fas fa-search search-icon"></i>
                            </button>
                            <input type="text" name="search" id="search" class="search-text"
                            placeholder="Judul Buku, Author, Gendre, ...">
                        </form>
                    </div>
                    <div class="wkwk">wkwk</div>

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

                        <li class="nav-item">
                            <i class="fas fa-shopping-cart nav-link text-body"></i>
                        </li>

                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link text-body" href="{{ route('login') }}">
                                Masuk
                                <i class="fas fa-sign-in-alt ml-1"></i>
                            </a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li> -->
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

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

        <footer class="">
            <div class="container">
                <div class="row">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit
                </div>
            </div>
        </footer>
    </div>
</body>

<script src="{{ url('js/layouts-app.js') }}"></script>
</html>
