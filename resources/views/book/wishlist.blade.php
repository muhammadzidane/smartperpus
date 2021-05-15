@extends('layouts.app')
@section('content')

<div class="borbot-gray-bold">
    <div class="w-maxc d-flex c-p text-grey">
        <div class="d-flex mr-4 active-authbook">
            <i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>
            <h4 class="hd-14">Daftar Wishlist</h4>
        </div>
        <div class="d-flex mr-4">
            <i class="fas fa-shopping-basket mr-2 text-green f-20"></i>
            <h4 class="hd-14">Keranjang Saya</h4>
        </div>
        <div class="d-flex mr-4">
            <i class="fas fa-user-circle mr-2 f-20 text-grey"></i>
            <h4 class="hd-14">Akun Saya</h4>
        </div>
    </div>
</div>

<div class="mt-5">
    <div class="book-deals mt-0 white-content-0">
        <div class="position-relative">
            <div class="ml-2">
                <div class="wishlist-search">
                    <span><i class="fa fa-search wishlist-search-icon" aria-hidden="true"></i></span>
                    <input type="search" placeholder="Cari wishlist anda">
                </div>
            </div>
        </div>

        <div class="my-2">
            @include('layouts.books',
                array('books' => \App\Models\Book::get())
            )
        </div>
</div>


</div>

@endsection
