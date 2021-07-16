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
        .my-navbar {
            background-color: #fff600;
            font-family: 'Righteous', cursive;
            padding: 8px 0;
        }

        .center {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-grip-line {
            margin-left: auto;
            display: none;
        }

        .search-icon,
        .responsive-search-icon {
            position: absolute;
            background-color: #ea5455;
            display: flex;
            width: 45px;
            height: 45px;
            border-radius: 100%;
            color: white;
            font-size: 18px;
        }

        .search-icon {
            left: -25px;
        }

        .responsive-search-icon {
            left: 0;
        }

        .responsive-search-text {
            width: 100%;
            box-sizing: border-box;
            border: 2px solid #ea5455;
            height: 45px;
            text-indent: 53px;
            border-top-right-radius: 23px;
            border-bottom-right-radius: 23px;
            border-top-left-radius: 23px;
            border-bottom-left-radius: 23px;
            outline: none;
        }

        @media screen and (max-width: 992px) {

            .navbar-content {
                display: none;
            }

            .navbar-grip-line {
                display: block;
            }
        }
    </style>
</head>

<body>
    <nav class="my-navbar row px-5">
        <div class="col-3 w-100">
            <a href="{{ route('home') }}" class="center"><img class="logo-img mr-auto" src="{{ asset('img/logo.png') }}"></a>
        </div>
        <div class="col-7">
            <div class="row">
                <div class="col-5">
                    <div class="circle-input w-100">
                        <form class="search-form" action="{{ route('search.books') }}" method="GET">
                            <div>
                                <div class="search-icon">
                                    <i class="fas fa-search m-auto"></i>
                                </div>
                                <input type="text" name="keywords" class="keywords search-text" placeholder="Judul Buku, Nama Author" autocomplete="off">
                                <input type="hidden" name="page" value="1">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-7 my-auto">
                    <div class="navbar-content">
                        <span class="mr-3">
                            Kategori <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </span>
                        <span class="mr-3">Best Seller</span>
                        <span class="mr-3">Buku Diskon</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-2 c-middle">
            <div class="my-auto ml-auto">
                @guest
                <button class="btn btn-red" data-toggle="modal" data-target="#modal-login">Masuk</button>
                @endguest

                @auth
                @include('layouts.auth-nav-login')
                @endauth
            </div>
            <div class="navbar-grip-line" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="fas fa-grip-lines"></i>
            </div>
        </div>
    </nav>
    <div class="collapse" id="collapseExample">
        <div class="responsive-navbar d-lg-none text-righteous">
            <div class="mt-3">
                <div class="text-center">
                    <form class="" action="{{ route('search.books') }}" method="GET">
                        <div>
                            <div class="responsive-search-icon">
                                <i class="fas fa-search m-auto"></i>
                            </div>
                            <input type="text" name="keywords" class="keywords responsive-search-text" placeholder="Judul Buku, Nama Author" autocomplete="off">
                            <input type="hidden" name="page" value="1">
                        </div>
                    </form>
                    <div class="my-2">
                        <div class="mr-3">
                            Kategori <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </div>
                        <div class="mr-3">Best Seller</div>
                        <div class="mr-3">Buku Diskon</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="white-content-0 borbot-gray-bold">
            <div class="white-content-header-2 flex-column">
                <h4 class="hd-14 m-0">Unggahan Bukti Pembayaran</h4>
            </div>
        </div>
        <div class="mt-c">
            <div class="white-content">
                <div class="row">
                    <div class="col-3">
                        <img class="w-100" src="{{ asset('img/form-register.jpg') }}" alt="gambar">
                    </div>
                    <div class="col-9">
                        <h4 class="hd-14">Lara Greyrat</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
