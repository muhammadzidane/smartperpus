@extends('layouts.app')
@section('content')

@include('content-header',
    array(
        'title' => 'Daftar Wishlist',
        'icon_html' => '<i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>',
    )
)

<div class="mt-md-4">
    <div>
        <div class="white-content">
            <div class="row justify-content-between borbot-gray pb-2">
                <div class="col-6 position-relative">
                    <i class="fa fa-search wishlist-search-icon" aria-hidden="true"></i>
                    <input class="wishlist-search-input w-100" type="search" placeholder="Cari wishlist anda">
                </div>
            </div>

            <div class="px-3">
                @include('layouts.books',
                    array(
                        'books' => $books,
                    )
                )
            </div>
        </div>
    </div>
</div>

@section('script')
    <script src="{{ asset('js/wishlist/index.js') }}"></script>
@endsection

@endsection
