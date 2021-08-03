@extends('layouts/app')
@section('content')

<div>Hasil Pencarian
    "<span id="search-text" class="tbold">
        {{ $keywords }}
    </span>"
</div>
<div class="row py-4">
    <div class="col-md-3 d-md-block d-none">
        <h4 class="hd-14">Filter <i class="fas fa-filter"></i></h4>
        <div class="filter-search white-content p-0">
            <div class="p-3">
                <h6 class="tbold borbot-gray-0 pb-2">Berdasarkan Kategori</h6>
                <div id="book-categories" class="p-2">
                    @forelse ($categories as $category)
                    <div class="tbold">{{ $category['name'] }} ({{ $category['book_count'] }})</div>

                    @empty
                    <div class="text-grey tbold">Pencarian kosong</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="white-content">
            <h6 class="tbold borbot-gray-0 pb-2">Minimum Harga</h6>
            <div class="form-group">
                <div class="d-flex">
                    <label class="tbold mt-2 mr-2" for="">Rp</label>
                    <input type="number" class="form-control m-0 min-price" name="min_price" placeholder="Mininum">
                </div>
            </div>

            <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
            <div class="form-group">
                <div class="d-flex">
                    <label class="tbold mt-2 mr-2" for="">Rp</label>
                    <input type="number" class="form-control m-0 max-price" name="max_price" placeholder="Maksimal">
                </div>
            </div>
            <button id="min-max-filter-button" class="d-flex btn btn-primary mt-2 ml-auto">Terapkan</button>
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
                <select id="sort-books">
                    <option value="relevan">Paling Relevan</option>
                    <option value="highest-rating">Rating Tertinggi</option>
                    <option value="lowest-rating">Rating Terendah</option>
                    <option value="lowest-price">Harga Terendah</option>
                    <option value="highest-price">Harga Tertinggi</option>
                </select>
            </div>
        </div>
        <div class="mt-4 d-md-none">
            <button type="button" class="w-50 btn btn-outline-yellow">Filter</button>
        </div>
        <div class="w-100">
            <!-- Tanda Filter -->
            <div id="search-filters" class="d-flex">
            </div>

            @if ($books->total() == 0)
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
        <div class="row">
            <div class="ml-auto mt-4">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
