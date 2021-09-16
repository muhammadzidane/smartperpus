@extends('layouts.app')
@section('content')

@include('content-header',
array(
'title' => 'Daftar Wishlist',
'icon_html' => '<i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>',
))

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')
    <div class="col-md-9">
        <div class="book-deals mt-0 white-content-0">
            <div class="position-relative">
                <i class="fa fa-search wishlist-search-icon" aria-hidden="true"></i>
                <input class="wishlist-search-input" type="search" placeholder="Cari wishlist anda">
            </div>

            <div class="mt-4 mx-4">
                @include('layouts.books',
                array(
                'books' => $books,
                )
                )
            </div>
        </div>
    </div>
</div>

@endsection
