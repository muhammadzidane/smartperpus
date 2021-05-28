@extends('layouts.app')
@section('content')

<div class="borbot-gray-bold overflow-auto">
    <div class="w-maxc d-flex c-p text-grey">
        <div class="d-flex mr-4">
            <i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>
            <h4 class="hd-14">Daftar Wishlist</h4>
        </div>
        <div class="d-flex mr-4">
            <i class="fas fa-shopping-basket mr-2 text-green f-20"></i>
            <h4 class="hd-14">Keranjang Saya</h4>
        </div>
        <div class="d-flex mr-4 active-authbook">
            <i class="fas fa-user-circle mr-2 f-20 text-grey"></i>
            <h4 class="hd-14">Akun Saya</h4>
        </div>
    </div>
</div>

<div class="row mt-md-4">
    <div class="col-lg-9">
        <div class="position-relative relative-none">
            <div class="chat-content w-100 shadow-custom" aria-labelledby="triggerId">
                <div class="chat-with-admin d-block">
                    <div class="borbot-gray-0 d-flex justify-content-between">
                        <h4 class="hd-14 p-1 ml-2 mt-1 c-middle">Tanya pada Admin</h4>
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
    </div>
    @include('account.purchases-and-inboxes', array('waiting_for_payments' => 'active-acc'))
</div>

@endsection
