@extends('layouts/app')
@section('content')

<div class="text-grey">
    <span>
        <i class="fas fa-search mr-1"></i>
        <span>Hasil Pencarian untuk</span>
    </span>
    <span id="search-text" class="tbold">"{{ request()->keywords ?? 'Semua Buku' }}"</span>
</div>

<form action="/books" method="GET">
    <div class="row flex-nowrap py-4">
        <div class="search-filters-md d-lg-block d-none">
            <div class="d-flex justify-content-between">
                <h5><i class="fas fa-filter"></i> Filter</h5>
                <div>
                    <button type="submit" class="form-reset">Reset</button>
                </div>
            </div>
            <div class="filter-sort mt-3 d-flex">
                <input type="checkbox" name="discount" id="discount" value="all" class="my-auto mr-2" {{ request()->discount == 'all' ? 'checked' : '' }}>
                <label for="discount" class="m-0 w-100">
                    <div class="text-left btn-none tbold pt-1">Diskon</div>
                </label>
            </div>
            <div class="white-content mt-4">
                <h6 class="tbold borbot-gray-0 pb-3">Berdasarkan Kategori</h6>

                @forelse ($categories as $category)
                <div id="filter-categories">
                    <label>
                        <div class="d-flex">
                            <input type="checkbox" name="category[]" value="{{ $category['id'] }}" class="mr-2 d-block my-auto" {{ is_array(request()->category) && in_array($category->id, request()->category) ? 'checked' : '' }}>
                            <span class="tbold">{{ $category->name }} ({{ $category->total_books }})</span>
                        </div>
                    </label>
                </div>

                @empty
                    <button class="btn-none text-grey tbold">Pencarian kosong</button>
                @endforelse
            </div>
            <div class="white-content mt-4">
                <h6 class="tbold borbot-gray-0 pb-2">Minimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0 min-price" name="min_price" placeholder="Mininum" value="{{ request()->min_price }}">
                    </div>
                </div>

                <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0 max-price" name="max_price" placeholder="Maksimal" value="{{ request()->max_price }}">
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="d-flex btn btn-outline-danger mt-4 ml-auto w-100">
                    <span class="mx-auto">Terapkan</span>
                </button>
            </div>
        </div>
        <div class="search-books-md">
            <div class="d-sm-flex justify-content-between borbot-gray-bold">
                <div>
                    <div class="d-flex text-grey tbold">
                        <div class="m-0 mr-2 search-content-active">
                            <a href="{{ url()->full() }}" class="text-decoration-none text-grey">BUKU ({{ $books->total() }})</a>
                        </div>
                        <div class="m-0 mr-2">
                            <a href="/authors?{{ preg_replace('/&page=[0-9]/i', '', request()->getQueryString()) }}" class="text-decoration-none text-grey">PENULIS ({{ $authors->count() }})</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4 mt-sm-0">
                    <div class="my-auto mr-1 text-grey">Ukutkan :</div>
                    <select class="sort-books" name="sort" onchange="this.form.submit()">
                        <option value="relevan" {{ request()->sort == 'relevan' ? 'selected' : '' }}>Paling Relevan</option>
                        <option value="highest-rating" {{ request()->sort == 'highest-rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="lowest-rating" {{ request()->sort == 'lowest-rating' ? 'selected' : '' }}>Rating Terendah</option>
                        <option value="lowest-price" {{ request()->sort == 'lowest-price' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="highest-price" {{ request()->sort == 'highest-price' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>
            </div>
            <div class="d-lg-none mt-4">
                <button type="button" class="w-100 btn btn-outline-danger" data-toggle="modal" data-target="#modal-filter">Filter</button>
            </div>
            <div class="mt-2">
                @if($books->isEmpty())
                <div class="w-50 mx-auto mt-4">
                    <h4 class="text-center mb-4">Hasil pencarian tidak ditemukan</h4>
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
    <input type="hidden" name="keywords" value="{{ request()->keywords }}">
    <input type="hidden" name="page" value="1">
</form>

<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-login">
            <div class="px-3 mb-3 d-flex justify-content-between">
                <h5 class="modal-title tred login-header mb">Filter</h5>
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
                                <div id="filter-categories">
                                    <label>
                                        <div class="d-flex">
                                            <input type="checkbox" name="category[]" value="{{ $category['id'] }}" class="mr-2 d-block my-auto" {{ (is_array(old('category')) && in_array($category['id'], old('category'))) ? 'checked' : '' }}>
                                            <span class="tbold">{{ $category['name'] }} ({{ $category['book_count'] }})</span>
                                        </div>
                                    </label>
                                </div>

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
                        <button type="submit" class="d-flex btn btn-outline-danger mt-4 ml-auto w-100">
                            <span class="mx-auto">Terapkan</span>
                        </button>
                        <input type="hidden" name="keywords" value="{{ request()->keywords }}">
                        <input type="hidden" name="page" value="1">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
