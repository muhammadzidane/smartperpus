@extends('layouts/app')
@section('content')

<div>Hasil Pencarian
    <span id="search-text" class="tbold">
        "{{ request('keywords') ?? 'Semua Buku' }}"
    </span>
</div>

<div class="py-4">
    <div class="borbot-gray-bold">
        <div class="search-value d-flex">
            <div class="mr-2 tred-bold">
                <a href="/books/{{ '?' . preg_replace('/&page=[0-9]/i', '', request()->getQueryString()) }}">Buku ({{ $book_count }})</a>
            </div>
            <div class="tred-bold search-content-active pb-2">
                <a href="{{ url()->full() }}">Penulis ({{ $authors->total() }})</a>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        @forelse ($authors as $author)
        <div class="col-lg-3 col-sm-6">
            <a href="{{ route('authors.show', array('author' => $author->id)) }}" class="text-decoration-none text-body">
                <div class="row white-content author-review">
                    <div class="col px-0">
                        <div>
                            <div class="w-50 mx-auto">
                                <img src="{{ asset('img/avatar-icon.png') }}" class="w-100">
                            </div>
                        </div>
                        <div class="mt-3">
                            <div>Nama : <span class="text-grey">{{ $author->name }}</span></div>
                            <div>Jumlah Buku : <span class="text-grey">{{ $author->books->count() }}</span></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="w-50 mx-auto mt-4 text-center">
            <h4 class="mb-4">Hasil pencarian tidak ditemukan</h4>
            <img class="w-75" src="{{ asset('img/no-data.png') }}">
        </div>
        @endforelse
    </div>
    <div class="row">
        <div class="ml-auto mt-4">
            {{ $authors->links() }}
        </div>
    </div>
</div>

@endsection
