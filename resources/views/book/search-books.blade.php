@extends('layouts/app')
@section('content')

<div>Hasil Pencarian
    "<span id="search-text" class="tbold">
        {{ $keywords }}
    </span>"
</div>

<form action="{{ route('books.index') }}" method="GET">
    <div class="row py-4">
        <div class="col-md-3 d-md-block d-none">
            <div class="d-flex justify-content-between">
                <h4 class="hd-14">Filter <i class="fas fa-filter"></i></h4>
                <div>
                    <button type="button" class="form-reset">Reset</button>
                </div>
            </div>
            <div class="white-content">
                <h6 class="tbold borbot-gray-0 pb-2">Berdasarkan Kategori</h6>
                <div>
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
                <!-- <button id="min-max-filter-button" class="d-flex btn btn-primary mt-2 ml-auto">Terapkan</button> -->
            </div>
            <div>
                <button type="submit" id="book-categories-button" class="d-flex btn btn-primary mt-4 ml-auto w-100">
                    <span class="mx-auto">Terapkan</span>
                </button>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between borbot-gray-bold">
                <div class="search-value d-flex pl-3">
                    <div class="mr-2 tred-bold active-authbook">
                        <span>Buku</span>
                        <span>({{ $books->total() }})</span>
                    </div>
                    <div class="tred-bold">
                        <span>Penulis</span>
                        <span>({{ '1 (nanti isi cok)' }})</span>
                    </div>
                </div>
                <div>
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
            <div class="mt-4 d-md-none">
                <button type="button" class="w-50 btn btn-outline-yellow">Filter</button>
            </div>
            <div class="w-100">
                @empty($books->total())
                <div class="w-50 mx-auto mt-4">
                    <h4 class="text-center mb-4">Hasil pencarian tidak ditemukan </h4>
                    <img class="w-100" src="{{ asset('img/no-data.png') }}">
                </div>

                @else
                <div id="books-search-value">
                    @include('layouts.books', compact('books'))
                </div>
                @endempty

            </div>
            <div class="row">
                <div class="ml-auto mt-4">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="keywords" value="{{ $keywords }}">
    <input type="hidden" name="page" value="{{ $books->currentPage() }}">
</form>

@endsection
