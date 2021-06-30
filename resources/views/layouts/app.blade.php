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
        <div>
            <nav class="cus-navbar">
                <div class="logo">
                    <a href="{{ route('home') }}"><img class="logo-img" src="{{ asset('img/logo.png') }}"></a>
                </div>
                <div class="circle-input">
                    <form class="search-form" action="{{ route('search.books') }}" method="GET">
                        <div>
                            <div class="search-icon">
                                <i class="fas fa-search m-auto"></i>
                            </div>
                            <input type="text" name="keywords" class="keywords search-text"
                            placeholder="Judul Buku, Nama Author" autocomplete="off">
                            <input type="hidden" name="page" value="1">
                        </div>
                    </form>
                </div>
                <div id="dropdown-navbar" class="self-middle ml-auto d-md-none">
                    <button class="btn-none"><i class="fas fa-grip-lines"></i></button>
                </div>
                <div class="cus-nav">
                    <ul class="ul-nav h-100">
                        <div class="d-flex h-100 align-items-center ml-3">
                            <li id="categories" class="h-100 c-middle">
                                Kategori <i class="fa fa-caret-down" aria-hidden="true"></i>
                                <div>
                                    <div class="d-flex">
                                        <div class="mr-5">
                                            @foreach (\App\Models\Category::get()->take(10) as $category)
                                                <div><a href="#" class="text-decoration-none text-body">{{ $category->name }}</a></div>
                                            @endforeach
                                        </div>
                                        <div>
                                            @foreach (\App\Models\Category::take(10)->skip(10)->get() as $category)
                                                <div><a href="#" class="text-decoration-none text-body">{{ $category->name }}</a></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li>Best Seller</li>
                            <li>Buku Diskon</li>
                        </div>
                        <div class="d-flex ml-auto align-items-center">
                            @guest
                                <li>
                                    <button id="login" class="btn btn-red" data-toggle="modal"
                                    data-target="#modal-login">Masuk</button></li>
                                </li>
                            @endguest
                            @auth
                                @include('layouts.auth-nav-login')
                            @endauth
                        </div>
                    </ul>
                </div>
            </nav>
            <div class="responsive-navbar d-md-none">
                <ul class="ul-nav d-block text-center">
                    <li>
                        <div class="circle-input">
                            <form class="search-form" action="{{ route('search.books') }}" method="GET">
                                <div>
                                    <div class="search-icon">
                                        <i class="fas fa-search m-auto"></i>
                                    </div>
                                    <input type="text" name="keywords" class="keywords search-text"
                                        placeholder="Judul Buku, Nama Author" autocomplete="off">
                                    <input type="hidden" name="page" value="1">
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="m-0">Kategori <i class="fa fa-caret-down" aria-hidden="true"></i></li>
                    <li class="m-0">Best Seller</li>
                    <li class="m-0">Buku Diskon</li>
                    @guest
                        <li>
                            <button id="login" class="btn-none tred" data-toggle="modal"
                            data-target="#modal-login">Masuk</button></li>
                        </li>
                        @endguest
                    @auth
                        @include('layouts.auth-nav-login')
                    @endauth
                </ul>
            </div>
        </div>

        @yield('carousel')

        <main class="py-4 container-lg">
            @yield('content')
        </main>

        <footer>
            <div>
                <div class="footer-logo">
                    <img class="w-15" src="{{ asset('img/logo.png') }}" alt="">
                </div>
                <div class="d-flex justify-content-center mt-5 bg-grey-2 py-4">
                    <div class="mr-5"><i class="fab fa-facebook-f"></i></div>
                    <div class="mr-5"><i class="fab fa-twitter"></i></div>
                    <div><i class="fab fa-instagram"></i></div>
                </div>
                <div class="white-content-0 pt-5">
                    <div class="container-lg d-flex flex-wrap">
                        <div class="footer-content">
                            <div>
                                <h4 class="hd-18">Tentang Smartperpus</h4>
                            </div>
                            <div class="mt-4">
                                <div class="text-grey">
                                    Smartperpus adalah toko online / offline yang menyediakan buku-buku berkualitas dan original yang
                                    tersedia dalam bentuk buku cetak dan ebook (file pdf). Toko offline berada di Jl. Pasir Honje No.
                                    221 RT004/01, Cimenyan, Kota Bandung dan telah berdiri sejak tahun 2021.
                                </div>
                            </div>
                        </div>
                        <div class="footer-content">
                            <div>
                                <div>
                                    <h4 class="hd-18">Pembayaran</h4>
                                </div>
                                <div class="mt-4">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <img class="w-100" src="{{ asset('img/transfer/bri-edit.png') }}">
                                        </div>
                                        <div class="mr-3">
                                            <img class="w-100" src="{{ asset('img/transfer/bri-edit.png') }}">
                                        </div>
                                        <div>
                                            <img class="w-100" src="{{ asset('img/transfer/bni-edit.png') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-c">
                                    <h4 class="hd-18">Pengiriman</h4>
                                </div>
                                <div class="mt-4">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <img class="w-100" src="{{ asset('img/couriers/jne.jpg') }}">
                                        </div>
                                        <div class="mr-3">
                                            <img class="w-100" src="{{ asset('img/couriers/pos.png') }}">
                                        </div>
                                        <div>
                                            <img class="w-100" src="{{ asset('img/couriers/tiki.jpg') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="footer-content">
                            <div>
                                <h4 class="hd-18">Kontak Kami</h4>
                            </div>
                            <div class="mt-4 text-grey">
                                <div><i class="fas fa-phone mr-1"></i><span>(WA) 0895364040902</span></div>
                                <div><i class="fas fa-phone mr-1"></i><span>(WA) 081321407123</span></div>
                                <div><i class="fas fa-phone mr-1"></i><span>(Telp) 0223938123</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-year"><h4 class="hd-14">Smartperpus - 2021</h4></div>
                </div>
            </div>
        </footer>

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
                            <div class="error-backend"></div>
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
                                    <div class="error-backend"></div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="d-flex">
                                    <input type="password" name="password" id="password" class="form-control-custom login-form"
                                     autocomplete="off" required>
                                    <button id="toggle-password" type="button" class="show-password btn-none bg-transparent">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="error-backend"></div>
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
    </div>
    @auth
        <div class="chat">
            <div class="chat-content"  aria-labelledby="triggerId">
                <div class="chat-with-admin">
                    <div class="borbot-gray-0 d-flex justify-content-between">
                        <h4 class="hd-16 p-1 ml-2 mt-1 c-middle">Tanya pada Admin</h4>
                        <button id="btn-chat-exit" class="btn-none c-middle mr-2"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
                    </div>
                    <div class="row ml-0">
                        <div class="col-md-4 p-0 overflow-auto">
                            <div class="borright-gray-0">
                                @can('viewAny', App\Models\User::class)
                                    <div class="testt">
                                        <div class="p-2">
                                            <form action="#" method="post">
                                                <input type="text" class="form-control-custom" placeholder="Cari user...">
                                            </form>
                                        </div>

                                        @php
                                            $chats = DB::select(
                                                'select user_chats.* from user_chats,
                                                (select user_id,max(created_at) as transaction_date
                                                    from user_chats
                                                    group by user_id) max_user
                                                where user_chats.user_id=max_user.user_id
                                                and user_chats.created_at=max_user.transaction_date'
                                            );
                                        @endphp

                                        <div class="user-chattings">
                                            @foreach ($chats as $chat)
                                                <div class="user-chat pl-3 py-3"
                                                  data-id="{{ App\Models\User::find($chat->user_id)->id }}">
                                                    <div class="tbold text-grey">{{ App\Models\User::find($chat->user_id)->first_name . ' '
                                                    . App\Models\User::find($chat->user_id)->last_name }}</div>
                                                    <div>{{ strlen($chat->text) <= 28 ? $chat->text : substr($chat->text, 1, 28) . '..' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @else
                                        <div class="p-2 testt">
                                            <img class="w-100" src="{{ asset('img/admin.png') }}">
                                        </div>
                                @endcan
                            </div>
                        </div>
                        <div class="col-md-8 pl-0">
                            <div>
                                <div class="chat-info">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <span class="f-10">
                                        <small class="tred">Pesan akan di balas pada jam kerja 09:00 - 22:00</small>
                                    </span>
                                    <span onclick="removeContent($('.chat-info'))" class="float-right mr-2">
                                        <small><i class="fa fa-times" aria-hidden="true"></i></small>
                                    </span>
                                </div>
                                <div class="container">
                                    <div class="chattings">
                                        <div class="mt-auto w-100">
                                            @foreach (App\Models\UserChat::where('user_id',
                                              Illuminate\Support\Facades\Auth::id())->get() as $chat)
                                                <div class="mt-3">
                                                    <div class="text-right">
                                                        <small>
                                                            <span class="tbold">Anda,</span>
                                                            <span> {{ $chat->created_at->isoFormat('dddd, D MMMM YYYY H:MM') }} WIB</span>
                                                        </small>
                                                    </div>
                                                    <div class="chat-msg-user">
                                                        <div class="chat-text-user">{{ $chat->text }}</div>
                                                    </div>
                                                </div>

                                                <!-- empty -->
                                                    <!-- <div class="chat-empty-img"><img class="w-100" src="{{ asset('img/chat.png') }}"></div> -->
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="type-message">
                    <div>
                        <form id="chats-store-form" action="{{ route('user-chats.store') }}" class="d-flex" method="post">
                            <i class="type-message-camera fa fa-camera-retro" aria-hidden="true"></i>
                            <input class="type-message-input" type="text" name="message" id="" placeholder="Ketik pesan..." autocomplete="off">
                            <button class="btn-none">
                                <i class="type-message-plane fas fa-paper-plane"></i>
                            </button>

                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    <div>
        <div class="click-in-buttom">
            @auth
                <button id="btn-chat" class="btn-none"><i class="far fa-comments"></i></button>
            @endauth
            <div class="click-to-the-top">
                <button id="click-to-the-top" class="btn-to-the-top d-flex ml-auto"><i class="to-the-top fa fa-caret-up" aria-hidden="true"></i></button>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/navbar.js') }}"></script>
<script src="{{ asset('js/helper-functions.js') }}"></script>

</html>
