@extends('layouts/app')

@section('carousel')
    @include('layouts/carousel')
@endsection


@section('content')

@if (session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
@endif

<div class="kategori-pilihan px-3">


    <h3 class="text-righteous p-2">Kategori Pilihan</h3>

    <div class="text-righteous overflow-auto">
        <div class="kp-1">
            <h5>Komik</h5>
            <img src="{{ url('img/kategori-pilihan/komik.jpg') }}">
        </div>
        <div class="kp-2">
            <h5>Pendidikan</h5>
            <img src="{{ url('img/kategori-pilihan/pendidikan.jpg') }}">
        </div>
        <div class="kp-1">
            <h5>Sejarah</h5>
            <img src="{{ url('img/kategori-pilihan/sejarah.jpg') }}">
        </div>
        <div class="kp-2">
            <h5>Novel</h5>
            <img src="{{ url('img/kategori-pilihan/novel.jpg') }}">
        </div>
    </div>
</div>

@include('layouts.book-deals',
    array(
        'title' => 'Buku Diskon',
        'books' => \App\Models\Book::where('discount', '!=', null)->get()
    )
)

@include('layouts.book-deals',
    array(
        'title' => 'Rekomendasi Komik / Manga',
        'books' => \App\Models\Category::where('name', 'komik')->first()->books
    )
)

<div class="chat">
    <div class="chat-content" aria-labelledby="triggerId">
        <div class="chat-with-admin">
            <div class="borbot-gray-0 d-flex justify-content-between">
                <h4 class="hd-16 p-1 ml-2 mt-1 c-middle">Tanya pada Admin</h4>
                <button id="btn-chat-exit" class="btn-none c-middle mr-2"><i class="fa fa-caret-down" aria-hidden="true"></i></button>
            </div>
            <div class="row ml-0">
                <div class="col-md-4 p-0">
                    <div class="borright-gray-0 h-100">
                        <div class="p-2">
                            <img class="w-100" src="{{ asset('img/admin.png') }}">
                        </div>
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
                                    <div>
                                        <div class="text-right"><small><span class="tbold">Anda</span>, 19 Juli 2000, 20:30 WIB</small></div>
                                        <div class="chat-msg-user">
                                            <div class="chat-text-user">stok buku cetak ny msih ada gk min ?</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-right"><small><span class="tbold">Anda</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-user">
                                            <div class="chat-text-user">Halo nama saya muhammmad zidane alsaadawi ganteng sedunia aawokawok</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-right"><small><span class="tbold">Anda</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-user">
                                            <div class="chat-text-user">min bales dong</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-left"><small><span class="tbold">Admin</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-admin">
                                            <div class="chat-text-admin">banyak gan stok nya mah</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-left"><small><span class="tbold">Admin</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-admin">
                                            <div class="chat-text-admin">banyak gan stok nya mah</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-left"><small><span class="tbold">Admin</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-admin">
                                            <div class="chat-text-admin">banyak gan stok nya mah</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-left"><small><span class="tbold">Admin</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-admin">
                                            <div class="chat-text-admin">banyak gan stok nya mah</div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-right"><small><span class="tbold">Admin</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-user">
                                            <div class="chat-img-user"><img class="w-50" src="{{ asset('img/book/detektif-conan-97.jpg') }}" alt="" srcset=""></div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-left"><small><span class="tbold">Admin</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-admin">
                                            <div class="chat-img-admin">
                                                <img class="w-50" src="{{ asset('img/book/detektif-conan-97.jpg') }}" alt="" srcset="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-left"><small><span class="tbold">Admin</span>, 19 Juli 2000, 20:31 WIB</small></div>
                                        <div class="chat-msg-admin">
                                            <div class="chat-text-admin">banyak gan stok nya mah</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="type-message">
                                <div class="d-flex">
                                    <i class="type-message-camera fa fa-camera-retro" aria-hidden="true"></i>
                                    <input class=" type-message-input" type="text" name="type-message" id="" placeholder="Ketik pesan...">
                                    <i class="type-message-plane fas fa-paper-plane"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="click-in-buttom">
        <button id="btn-chat" class="btn-none"><i class="far fa-comments"></i></button>
        <div class="click-to-the-top">
            <button id="click-to-the-top" class="btn-to-the-top d-flex ml-auto"><i class="to-the-top fa fa-caret-up" aria-hidden="true"></i></button>
        </div>
    </div>
</div>

@endsection
