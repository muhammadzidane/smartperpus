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
        <nav class="cus-navbar">
            <div class="logo">
                <a href="{{ route('home') }}"><img class="logo-img" src="{{ asset('img/logo.png') }}"></a>
            </div>
            <div class="circle-input">
                <div>
                    <form class="search-form" action="{{ route('books.index') }}" method="GET">
                        <div>
                            <div class="search-icon">
                                <i class="fas fa-search m-auto"></i>
                            </div>
                            <input type="text" name="keywords" class="keywords search-text" placeholder="Judul Buku, Nama Author" autocomplete="off">
                            <input type="hidden" name="page" value="1">
                        </div>
                    </form>
                </div>
                <div class="nav-book-search">
                    <div class="ml-2 pb-1">Buku</div>
                    <div class="nav-book-search-values">
                    </div>
                </div>
            </div>
            <div class="cus-nav">
                <ul class="ul-nav h-100">
                    <div id="nav-categories" class="d-flex h-100 align-items-center ml-3">
                        <li id="categories" class="h-100 c-middle">
                            Kategori <i class="fa fa-caret-down" aria-hidden="true"></i>
                            <div>
                                <div class="d-flex">
                                    <div class="mr-5">
                                        @foreach (App\Models\Category::has('books')->get() as $category)
                                        <div><a href="{{ route('books.index', array('category' => array($category->id))) }}" class="text-decoration-none text-body">{{ $category->name }}</a></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li><a href="#" class="text-decoration-none text-body">Best Seller</a></li>
                        <li><a href="#" class="text-decoration-none text-body">Buku Diskon</a></li>

                        @cannot('viewAny', 'App\\Models\User')
                        <li class="nav-bell">
                            <div class="dropdown">
                                <button class="btn-none" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="far fa-bell"></i>
                                </button>
                                <div class="dropdown-menu nav-dropdown" aria-labelledby="dropdownMenuButton">
                                    <div class="nav-dropdown-status">
                                        <a class="text-decoration-none" href="{{ route('status.waiting.for.payment') }}">Menunggu Pembayaran
                                            @if (Illuminate\Support\Facades\DB::table('book_user')
                                            ->where('user_id', Auth::id())->where('payment_status', 'waiting_for_confirmation')->get()->count() != 0)
                                            <span class="nav-dropdown-waiting">
                                                {{
                                                        Illuminate\Support\Facades\DB::table('book_user')
                                                        ->where('user_id', Auth::id())
                                                        ->where('confirmed_payment', false)
                                                        ->where('payment_status', 'waiting_for_confirmation')
                                                        ->get()->count()
                                                    }}
                                            </span>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="nav-dropdown-status">
                                        <a class="text-decoration-none" href="{{ route('status.on.process') }}">
                                            Sedang Diproses
                                            @if (Illuminate\Support\Facades\DB::table('book_user')
                                            ->where('user_id', Auth::id())->where('payment_status', 'order_in_process')->get()->count() != 0)
                                            <span class="nav-dropdown-waiting">
                                                {{
                                                        Illuminate\Support\Facades\DB::table('book_user')
                                                        ->where('user_id', Auth::id())
                                                        ->where('payment_status', 'order_in_process')
                                                        ->get()->count()
                                                    }}
                                            </span>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="nav-dropdown-status">
                                        <a class="text-decoration-none" href="{{ route('status.on.delivery') }}">Sedang Dikirim</a>
                                        @if (Illuminate\Support\Facades\DB::table('book_user')
                                        ->where('user_id', Auth::id())->where('payment_status', 'being_shipped')->get()->count() != 0)
                                        <span class="nav-dropdown-waiting">
                                            {{
                                                    Illuminate\Support\Facades\DB::table('book_user')
                                                    ->where('user_id', Auth::id())
                                                    ->where('payment_status', 'being_shipped')
                                                    ->get()->count()
                                                }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="nav-dropdown-status">
                                        <a class="text-decoration-none" href="{{ route('status.success') }}">Telah Sampai</a>
                                        @if (Illuminate\Support\Facades\DB::table('book_user')
                                        ->where('user_id', Auth::id())->where('payment_status', 'arrived')->get()->count() != 0)
                                        <span class="nav-dropdown-waiting">
                                            {{
                                                    Illuminate\Support\Facades\DB::table('book_user')
                                                    ->where('user_id', Auth::id())
                                                    ->where('payment_status', 'arrived')
                                                    ->get()->count()
                                                }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcannot
                    </div>
                    <div id="nav-login" class="d-flex ml-auto align-items-center">
                        @guest
                        <li>
                            <button id="login" class="btn btn-red" data-toggle="modal" data-target="#modal-login">Masuk</button>
                        </li>
                        </li>
                        @endguest

                        @auth
                        @include('layouts.auth-nav-login')
                        @endauth
                    </div>
                    <div class="navbar-grip-line d-lg-none ml-auto" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fas fa-grip-lines"></i>
                    </div>
                </ul>
            </div>
        </nav>

        <div class="collapse d-lg-none borbot-gray-bold" id="collapseExample">
            <div class="mt-3">
                <div class="text-center">
                    <form class="" action="{{ route('books.index') }}" method="GET">
                        <div>
                            <div class="responsive-search-icon">
                                <i class="fas fa-search m-auto"></i>
                            </div>
                            <input type="text" name="keywords" class="keywords responsive-search-text" placeholder="Judul Buku, Nama Author" autocomplete="off">
                        </div>
                    </form>
                    <div class="my-2">
                        <div>
                            Kategori <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </div>
                        <div>Best Seller</div>
                        <div>Buku Diskon</div>
                        @guest
                        <div>
                            <button class="btn-none text-danger tbold" data-toggle="modal" data-target="#modal-login">Masuk</button>
                        </div>

                        @else
                        @include('layouts.auth-nav-login')
                        @endguest
                    </div>
                </div>
            </div>
        </div>

        <!-- Error messages from backend -->
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            <div>
                <strong>{{ $error }}</strong>
            </div>
            @endforeach
        </div>
        @endif

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
                    <div class="footer-year">
                        <h4 class="hd-14">Smartperpus - 2021</h4>
                    </div>
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
                                <input type="email" id="email" name="email" class="form-control-custom login-form" value="{{ old('email') }}" required>

                                @error('email')
                                <div class="error-backend"></div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="d-flex">
                                    <input type="password" name="password" id="password" class="form-control-custom login-form" autocomplete="off" required>
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
    <div class="chat" data-role="{{ Illuminate\Support\Facades\Auth::user()->role }}">
        <div class="chat-content" aria-labelledby="triggerId">
            <div class="chat-with-admin">
                <div class="borbot-gray-0 d-flex justify-content-between">
                    <h4 class="hd-16 p-1 ml-2 mt-1 c-middle">
                        <i @can('viewAny', App\Models\User::class) id="chat-back" @endcan class="d-none fa fa-arrow-left mr-2"></i>
                        Tanya pada Admin
                    </h4>
                    <button id="btn-chat-exit" class="btn-none c-middle mr-2">
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="row ml-0">
                    <div @can('viewAny', App\Models\User::class) id="user-chats" @endcan class="col-md-4 p-0">
                        <div class="borright-gray-0">
                            @can('viewAny', App\Models\User::class)
                            <div class="testt">
                                <div class="p-2">
                                    <input id="chat-search-user" type="text" class="chat-search-user-input" placeholder="Cari user..." autocomplete="off">
                                </div>
                                <div class="user-chattings">
                                    @php
                                    $chats = DB::select(
                                    'select user_chats.* from user_chats,
                                    (select user_id,max(created_at) as transaction_date
                                    from user_chats
                                    group by user_id) max_user
                                    where user_chats.user_id=max_user.user_id
                                    and user_chats.created_at=max_user.transaction_date
                                    ORDER BY user_chats.created_at DESC'
                                    );

                                    @endphp

                                    @foreach ($chats as $chat)
                                    <div class="user-chat pl-3 pr-2 py-2" data-id="{{ App\Models\User::find($chat->user_id)->id }}">
                                        <div class="d-flex justify-content-between">
                                            <div class="tbold text-grey">{{ App\Models\User::find($chat->user_id)->first_name . ' '
                                                    . App\Models\User::find($chat->user_id)->last_name }}</div>
                                            <div class="user-chat-time">
                                                @if (Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($chat->created_at)) >= 1)
                                                <small>{{ Carbon\Carbon::parse($chat->created_at)->format('y/m/d') }}</small>
                                                @else
                                                <small>{{ Carbon\Carbon::parse($chat->created_at)->format('H:i') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <span id="user-chats-text">
                                                @if ($chat->text == null)
                                                <span>Mengirim gambar...</span>
                                                @endif

                                                <span>
                                                    {{ strlen($chat->text) <= 18 ? $chat->text : substr($chat->text, 1, 18) . '...' }}
                                                </span>
                                            </span>

                                            @if (App\Models\UserChat::where('user_id', $chat->user_id)
                                            ->where('read', false)->get()->count() !== 0)

                                            <span class="user-chat-notifications">
                                                <small>
                                                    {{
                                                        App\Models\UserChat::where('user_id', $chat->user_id)
                                                        ->where('read', false)->get()->count()
                                                    }}
                                                </small>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @else
                            <div class="p-2 testt bg-transparent">
                                <img class="w-100" src="{{ asset('img/admin.png') }}">
                            </div>
                            @endcan
                        </div>
                    </div>
                    <div class="col-md-8 pl-0">
                        <div>
                            <div class="chat-info">
                                <span class="f-10">

                                    <!-- Message error backend - Image only -->
                                    @if (session('pesan'))
                                    <small class="tred">Hanya bisa mengirim file gambar</small>

                                    @else
                                    <i class="fa fa-info-circle"></i>
                                    <small class="tred">Pesan akan di balas pada jam kerja 09:00 - 22:00</small>
                                    <div class="chat-delete" class="bg-dark">
                                        <button id="chat-delete-button" class="btn-none" class="btn-none mr-4">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <form id="chat-delete-form" method="POST" action="{{ route('user-chats.destroy',
                                            array('user_chat' => Illuminate\Support\Facades\Auth::id())) }}">

                                            <button class="chat-delete-action btn-none" type="submit">Hapus pesan</button>
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </div>
                                    @endif
                                </span>
                            </div>
                            <div class="container-md position-relative">
                                <div id='user-chats-error-image' class="d-none">Hanya bisa mengirim file gambar</div>
                                <div class="chattings" data-id="{{ Illuminate\Support\Facades\Auth::id() }}">
                                    <div id="user-chat-send-img" class="d-none">
                                        <div><button id='user-send-img-cancel' class="btn-none mb-3"><i class="fa fa-times"></i></button></div>
                                        <img id="user-chat-img">
                                    </div>
                                    <div id="user-chat-send-img-input" class="d-none">
                                        <input class="user-chat-img-information" type="text" name="message" placeholder="Tambah keterangan..." autocomplete="off">
                                        <button id="user-chat-store-send-img" type="button" class="btn-none">
                                            <i class="type-message-plane fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                    @cannot('viewAny', App\Models\User::class)
                                    <div id="menu-chattings" class="mt-auto w-100">

                                        @foreach (App\Models\AdminChat::where('user_id',
                                        Illuminate\Support\Facades\Auth::id())->get(); as $chattings)

                                        @php
                                        Illuminate\Support\Facades\Auth::user()->user_chats->push($chattings)
                                        @endphp
                                        @endforeach

                                        @foreach (Illuminate\Support\Facades\Auth::user()->user_chats->sortBy('created_at') as $chat)
                                        <div class="mt-4">
                                            <div class="{{ $chat->getTable() == 'user_chats' ? 'text-right' : 'text-left' }}">
                                                <small>
                                                    @if ($chat->getTable() == 'admin_chats')
                                                    <span class="tbold">Admin, </span>
                                                    @endif

                                                    {{ $chat->created_at->isoFormat('dddd, D MMMM YYYY H:m') }}
                                                </small>
                                            </div>

                                            @if ($chat->image)
                                            <div class="{{ $chat->getTable() == 'user_chats' ? 'chat-img-user' : 'chat-img-admin' }}">
                                                <img class="w-100 d-block mb-3" src="{{ asset('storage/chats/' . $chat->image) }}">
                                                @if ($chat->text != null)
                                                <div class="{{ $chat->getTable() == 'user_chats' ? 'chat-text-user'
                                                    : 'chat-text-admin' }}">
                                                    {{ $chat->text }}
                                                </div>
                                                @endif
                                            </div>

                                            @else
                                            <div class="{{ $chat->getTable() == 'user_chats' ? 'chat-msg-user' : 'chat-msg-admin' }}">
                                                <div class="{{ $chat->getTable() == 'user_chats' ? 'chat-text-user' : 'chat-text-admin' }}">
                                                    {{ $chat->text }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach

                                    </div>
                                    @else
                                    <div id="menu-chattings" class="mt-auto w-100"></div>
                                    @endcannot
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="type-message">
                <div>
                    @cannot('viewAny', App\Models\User::class)
                    <form id="user-chats-store-form" enctype="multipart/form-data" action="{{ route('user-chats.store') }}" class="d-flex chats-store-form" method="post">

                        @else
                        <form id="admin-chats-store-form" enctype="multipart/form-data" action="{{ route('user-chats.store') }}" class="d-flex chats-store-form" method="post">

                            @endcannot
                            <span class="mt-2">
                                <label>
                                    <i class="type-message-camera fa fa-camera-retro" aria-hidden="true"></i>
                                    <input id="user-chat-send-photo" type="file" name="photo" class="d-none" accept="image/png, image/jpeg, image/jpg">
                                </label>
                            </span>
                            <input class="type-message-input" type="text" name="message" id="" placeholder="Ketik pesan..." autocomplete="off">
                            <button id="chats-submit" class="btn-none">
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
            <div>
                <button id="btn-chat" class="btn-none {{ Illuminate\Support\Facades\Auth::user()->role == 'guest' ? 'btn-chat-guest' : '' }}">
                    <i class="far fa-comments"></i>
                    @can('viewAny', App\Models\User::class)

                    @if (App\Models\UserChat::where('read', 'false')->get()->count() !== 0)
                    <div class="btn-chat-unread">
                        {{ App\Models\UserChat::where('read', 'false')->get()->count() }}
                    </div>
                    @endif

                    @else

                    @if (Illuminate\Support\Facades\Auth::user()->admin_chats->where('read', 'false')->count() !== 0)
                    <div class="btn-chat-unread">
                        {{ Illuminate\Support\Facades\Auth::user()->admin_chats->where('read', 'false')->count() }}
                    </div>
                    @endif
                    @endcan
                </button>
            </div>
            @endauth
            <div class="click-to-the-top">
                <button id="click-to-the-top" class="btn-to-the-top d-flex ml-auto">
                    <i class="to-the-top fa fa-caret-up"></i>
                </button>
            </div>
        </div>
    </div>
</body>

<script src="{{ asset('js/navbar.js') }}"></script>
<script src="{{ asset('js/helper-functions.js') }}"></script>

</html>
