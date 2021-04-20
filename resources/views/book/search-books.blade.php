@extends('layouts/app')
@section('content')

<div class="wkwk1 py-4">
    <div class="d-flex flex-column">
        <div class="white-content m-0">
            <h6 class="tbold mt-1">Filter <i class="fas fa-filter"></i></h6>
        </div>
        <div class="filter-search white-content p-0">
            <div class="p-3">
                <h6 class="tbold borbot-gray-0 pb-2">Berdasarkan Kategori</h6>
                <div id="book-categories" class="p-2">
                    @php
                        $arr_categories = array();
                    @endphp
                    @foreach (\App\Models\Book::where('name', 'LIKE', '%' . $_GET['keywords'] . '%')->with('categories')->get() as $book)
                        @foreach ($book->categories as $category)
                            @php
                            array_push($arr_categories, $category->name);
                            @endphp
                        @endforeach
                    @endforeach

                    @php
                        $count_categories = array_count_values($arr_categories);
                    @endphp

                    @forelse ($count_categories as $category_key => $category_val)
                        <div class="c-p">{{ $category_key . ' (' . $category_val . ')' }}</div>
                        @empty
                        <div class="tred-bold">Kosong</div>
                    @endforelse
                    <div><button class="btn p-0 tbold">lihat lainya</button></div>
                </div>
            </div>
        </div>
        <div class="white-content">
            <form class="p-2" action="">
                <h6 class="tbold borbot-gray-0 pb-2">Minimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0" name="min_price" id="min-price"
                            placeholder="Mininum">
                    </div>
                </div>

                <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0" name="max_price" id="max-price"
                            placeholder="Maksimal">
                    </div>
                </div>
                <button id="min-max-value" type="submit" class="d-flex btn btn-primary mt-2 ml-auto">Terapkan</button>
            </form>
        </div>

        <div class="white-content">
            <h6 class="tbold borbot-gray-0 pb-2">Rating</h6>
            <div class="p-2">
                <div data-filter-star="4" class="filter-star-search c-p">
                    @for ($i = 0; $i < 4; $i++) <i class="fa fa-star"></i>
                        @endfor
                        <span> - ke atas</span>
                </div>
            </div>
        </div>

    </div>
    <div class="ml-5">
        <div class="d-flex justify-content-between borbot-gray-bold">
            <div class="search-value d-flex pl-3">
                <div class="mr-2 tred-bold active-authbook">
                    <span>Buku</span>
                    <span>({{ \App\Models\Book::where('name', 'like', '%' . $_GET['keywords'] . '%')->get()->count() }})</span>
                </div>
                <div class="tred-bold">
                    <span>Penulis</span>
                    <span>({{ \App\Models\Author::where('name', 'like', '%' . $_GET['keywords'] . '%')->get()->count() }})</span>
                </div>
            </div>
            <div>Hasil Pencarian
                "<span id="search-text" class="tbold">
                    {{ strlen($_GET['keywords']) <= 40 ? $_GET['keywords'] : substr($_GET['keywords'], -0 , 35) . '.....' }}
                </span>"
            </div>
            <select id="sort-books">
                <option>Paling Laris</option>
                <option value="highest-rating">Rating Tertinggi</option>
                <option value="lowest-price">Harga Terendah</option>
                <option value="highest-price">Harga Tertinggi</option>
            </select>
        </div>
        <div id="search-filters" class="d-flex">
        </div>
        <div id="book-search">
            @include('layouts.books', array('books' => \App\Models\Book::where('name', 'like', '%' . $_GET['keywords'] .
              '%')->take(1)->skip(0)->get()))
        </div>
        <div class="d-flex">
            <div class="ml-auto">
                <div id="pagination" class="pagination-custom">
                    <div id="pagination-prev"><i class="fa fa-caret-left"></i></div>
                    <div id="pagination-number" class="d-flex">
                    </div>
                    <div id="pagination-next"><i class="fa fa-caret-right"></i></div>
                    <!-- <div id="pagination-prev"><i class="fa fa-caret-left"></div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="click-to-the-top">
    <button class="btn-to-the-top d-flex ml-auto"><i class="to-the-top fa fa-caret-up"></i></button>
</div>

@endsection
