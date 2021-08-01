@extends('layouts.app')
@section('content')

<div class="borbot-gray-bold">
    <div class="w-maxc d-flex c-p text-grey pb-1">
        <div class="d-flex mr-4 active-authbook">
            <i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>
            <h4 class="hd-14">Daftar Wishlist</h4>
        </div>
    </div>
</div>

<div class="mt-4">
    <div class="book-deals mt-0 white-content-0">
        <div class="position-relative">
            <i class="fa fa-search wishlist-search-icon" aria-hidden="true"></i>
            <input class="wishlist-search-input" type="search" placeholder="Cari wishlist anda">
        </div>

        <div class="my-2">
            @include('layouts.books',
            array('books' => $books)
            )
        </div>
    </div>


</div>

@endsection
