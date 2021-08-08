@extends('layouts/app')
@section('content')

<div>Hasil Pencarian
    <span id="search-text" class="tbold">
        "{{ $keywords ?? 'Semua Buku' }}"
    </span>
</div>

<form action="{{ route('books.index') }}" method="GET">
    <div class="row py-4">
        <div class="col-md-3 d-md-block d-none">
            <div class="d-flex justify-content-between">
                <h4 class="hd-14">Filter <i class="fas fa-filter"></i></h4>
                <div>
                    <button type="submit" class="form-reset">Reset</button>
                </div>
            </div>
            <div class="white-content">
                <h6 class="tbold borbot-gray-0 pb-3">Berdasarkan Kategori</h6>
                <button class="btn btn-none pl-0" type="button" data-toggle="collapse" data-target="#categories-filter" aria-expanded="false" aria-controls="categories-filter">
                    Lihat Semua
                </button>

                <div class="collapse" id="categories-filter">
                    @forelse ($categories as $category)

                    @if ($category['book_count'] != 0)
                    <div id="filter-categories">
                        <label>
                            <div class="d-flex">
                                <input type="checkbox" name="category[]" value="{{ $category['id'] }}" class="mr-2 d-block my-auto" {{ (is_array(old('category')) && in_array($category['id'], old('category'))) ? 'checked' : '' }}>
                                <span class="tbold">{{ $category['name'] }} ({{ $category['book_count'] }})</span>
                            </div>
                        </label>
                    </div>
                    @endif

                    @empty
                    <button class="btn-none text-grey tbold">Pencarian kosong</button>
                    @endforelse
                </div>
            </div>
            <div class="white-content mt-4">
                <h6 class="tbold borbot-gray-0 pb-2">Minimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0 min-price" name="min_price" placeholder="Mininum" value="{{ old('min_price') }}">
                    </div>
                </div>

                <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0 max-price" name="max_price" placeholder="Maksimal" value="{{ old('max_price') }}">
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="d-flex btn btn-primary mt-4 ml-auto w-100">
                    <span class="mx-auto">Terapkan</span>
                </button>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-sm-flex justify-content-between borbot-gray-bold">
                <div class="search-value d-flex">
                    <div class="mr-2 tred-bold search-content-active">
                        <a href="{{ route('books.index') }}">Buku ({{ $books->total() }})</a>
                    </div>
                    <div class="tred-bold">
                        <a href="/authors/{{ '?' . preg_replace('/&page=[0-9]/i', '', request()->getQueryString()) }}">Penulis ({{ $authors->count() }})</a>
                    </div>
                </div>
                <div class="mt-4 mt-sm-0">
                    <span class="mr-2 text-grey">Ukutkan</span>
                    <select id="sort-books" name="sort">
                        <option value="relevan" {{ old('sort') == 'relevan' ? 'selected' : '' }}>Paling Relevan</option>
                        <option value="highest-rating" {{ old('sort') == 'highest-rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="lowest-rating" {{ old('sort') == 'lowest-rating' ? 'selected' : '' }}>Rating Terendah</option>
                        <option value="lowest-price" {{ old('sort') == 'lowest-price' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="highest-price" {{ old('sort') == 'highest-price' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>
            </div>
            <div class="d-md-none mt-4">
                <button type="button" class="w-50 btn btn-outline-yellow" data-toggle="modal" data-target="#modal-filter">Filter</button>
            </div>
            <div class="mt-2">
                @if(empty($books->total()) || $books->isEmpty())
                <div class="w-50 mx-auto mt-4">
                    <h4 class="text-center mb-4">Hasil pencarian tidak ditemukan </h4>
                    <img class="w-100" src="{{ asset('img/no-data.png') }}">
                </div>

                @else
                <div id="books-search-value">
                    @include('layouts.books', compact('books'))
                </div>
                @endif
            </div>

            @if ($books->isNotEmpty())
            <div class="row">
                <div class="ml-auto mt-4">
                    {{ $books->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
    <input type="hidden" name="keywords" value="{{ $keywords }}">
    <input type="hidden" name="page" value="1">
</form>

<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-login">
            <div class="px-3 mb-3 d-flex justify-content-between">
                <h5 class="modal-title tred login-header">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('books.index') }}" method="GET">
                    <div>
                        <div class="text-right mb-3">
                            <button type="submit" class="form-reset">Reset</button>
                        </div>
                        <h6 class="tbold borbot-gray-0 pb-3">Berdasarkan Kategori</h6>
                        <button class="btn btn-none pl-0" type="button" data-toggle="collapse" data-target="#categories-filter" aria-expanded="false" aria-controls="categories-filter">
                            Lihat Semua
                        </button>

                        <div class="collapse" id="categories-filter">
                            @forelse ($categories as $category)

                            @if ($category['book_count'] != 0)
                            <div id="filter-categories">
                                <label>
                                    <div class="d-flex">
                                        <input type="checkbox" name="category[]" value="{{ $category['id'] }}" class="mr-2 d-block my-auto" {{ (is_array(old('category')) && in_array($category['id'], old('category'))) ? 'checked' : '' }}>
                                        <span class="tbold">{{ $category['name'] }} ({{ $category['book_count'] }})</span>
                                    </div>
                                </label>
                            </div>
                            @endif

                            @empty
                            <button class="btn-none text-grey tbold">Pencarian kosong</button>
                            @endforelse
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="tbold borbot-gray-0 pb-2">Minimum Harga</h6>
                        <div class="form-group">
                            <div class="d-flex">
                                <label class="tbold mt-2 mr-2" for="">Rp</label>
                                <input type="number" class="form-control m-0 min-price" name="min_price" placeholder="Mininum" value="{{ old('min_price') }}">
                            </div>
                        </div>

                        <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                        <div class="form-group">
                            <div class="d-flex">
                                <label class="tbold mt-2 mr-2" for="">Rp</label>
                                <input type="number" class="form-control m-0 max-price" name="max_price" placeholder="Maksimal" value="{{ old('max_price') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="d-flex btn btn-primary mt-4 ml-auto w-100">
                            <span class="mx-auto">Terapkan</span>
                        </button>
                        <input type="hidden" name="keywords" value="{{ $keywords }}">
                        <input type="hidden" name="page" value="1">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
