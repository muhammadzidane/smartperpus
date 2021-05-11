@extends('layouts/app')
@section('content')

<div class="wkwk1 py-4">
    <div  class="filters d-flex flex-column">
        <div class="white-content m-0">
            <h6 class="tbold text-grey mt-1">Filter <i class="fas fa-filter"></i></h6>
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
                        <input type="number" class="form-control m-0 min-price" name="min_price"
                            placeholder="Mininum">
                    </div>
                </div>

                <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0 max-price" name="max_price"
                            placeholder="Maksimal">
                    </div>
                </div>
                <button type="submit" class="min-max-value d-flex btn btn-primary mt-2 ml-auto">Terapkan</button>
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
        <div id="filter-button" class="d-none ml-3 mb-3">
            <button class="btn btn-sm w-15 text-grey btn-outline-grey" data-toggle="modal" data-target="#modal-filter">Filter <i class="fas fa-filter"></i></button>

            <div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered p-5" role="document">
                    <div class="modal-content modal-content-login">
                        <div class="px-3 mb-4 d-flex justify-content-between">
                            <h4 class="modal-title hd-18 tred login-header">Filter</h4>
                            <button id="login-exit" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div  class="filters d-flex flex-column">
                                <div class="filter-search white-content p-0 m-0">
                                    <div class="p-3">
                                        <h6 class="tbold borbot-gray-0 pb-2">Berdasarkan Kategori</h6>
                                        <div id="book-categories" class="p-2">
                                            @php
                                                $arr_categories = array();
                                            @endphp
                                            @foreach (\App\Models\Book::first()->with('categories')->get() as $book)
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
                                                <input type="number" class="form-control m-0 min-price" name="min_price"
                                                    placeholder="Mininum">
                                            </div>
                                        </div>

                                        <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                                        <div class="form-group">
                                            <div class="d-flex">
                                                <label class="tbold mt-2 mr-2" for="">Rp</label>
                                                <input type="number" class="form-control m-0 max-price" name="max_price"
                                                    placeholder="Maksimal">
                                            </div>
                                        </div>
                                        <button type="submit" class="min-max-value d-flex btn btn-primary mt-2 ml-auto">Terapkan</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
