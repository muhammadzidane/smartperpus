@extends('layouts.app')
@section('content')

@include('content-header',
array(
'title' => 'Akun Saya',
'icon_html' => '<i class="user-icon fas fa-user-circle text-grey"></i>'
))

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')
    <div class="col-md-9">
        <div class="row">
            <div class="col-12">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="status-search">
                        <button class="status-search-icon btn-none p-0">
                            <i class="fa fa-search d-none d-md-block" aria-hidden="true"></i>
                        </button>
                        <input name="review_keywords" class="status-search-input" type="text" placeholder="Cari berdasarkan nama produk atau nomer pesanan" value="{{ request()->review_keywords }}">
                    </div>
                </form>

                @if (request()->review_keywords)
                <div class="text-grey mt-4">
                    <span>Hasil pencarian untuk</span>
                    <span class="tbold">"{{ request()->review_keywords }}"</span>
                    <span>. Menampilkan <span class="tbold">{{ $books->count() }}</span> hasil </span>
                </div>
                @endif
            </div>
        </div>
        <div>
            @forelse ($books as $book)
                <div class="white-content-0 mt-4">
                    <div class="p-3">
                        <div class="text-grey d-flex justify-content-between borbot-gray-0 pb-2">
                            <div>No Pesanan: {{ $book->invoice }}</div>
                            <div>
                                <span class="tred-bold">SELESAI </span>-
                                <span>{{ $book->completed_date }}</span>
                            </div>
                        </div>
                        <div class="d-flex mt-3">
                            <div class="review-image mr-3">
                                <img src="{{ asset('storage/books/' . $book->image ) }}" alt="">
                            </div>
                            <div class="overflow-auto wb-word w-100">
                                <div>{{ $book->name }}</div>
                                <div class="mt-2 d-flex align-items-center">
                                    @for ($i=0; $i < 5; $i++)
                                        @if ($book->rating > $i)
                                            <i class="review-star-icon star-icon-color fa fa-star mr-1"></i>

                                        @else
                                            <i class="review-star-icon star-icon-color far fa-star mr-1"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2">{{ $book->rating_date }}</span>
                                </div>
                                <div class="mt-1 tred-bold">{{ $rating_text[$book->rating - 1] }}</div>
                                <div class="mt-1">Ulasan :</div>
                                <div class="mt-1">{{ $book->review }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @include('empty-image', array('title' => 'Belum ada ulasan'))
            @endforelse
        </div>
        <div class="d-flex justify-content-end mt-4">{{ $books->links() }}</div>
    </div>
</div>


@endsection
