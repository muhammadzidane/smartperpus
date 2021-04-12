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
                <div class="p-2">
                    @foreach (\App\Models\Category::orderBy('name')->get()->take(4) as $category)
                    <div>{{ $category->name }}</div>
                    @endforeach
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
                        <input type="number" class="form-control m-0" name="min_price" id="min_price" placeholder="Mininum">
                    </div>
                </div>

                <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0" name="max_price" id="max_price" placeholder="Maksimal">
                    </div>
                </div>
                <button id="min-max-value" type="submit" class="d-flex btn btn-primary mt-2 ml-auto">Terapkan</button>
            </form>
        </div>

        <div class="white-content">
            <h6 class="tbold borbot-gray-0 pb-2">Rating</h6>
            <div class="p-2">
                <div>
                    @for ($i = 0; $i < 5; $i++)
                        <i class="fa fa-star" aria-hidden="true"></i>
                    @endfor
                </div>
                <div>
                    @for ($i = 0; $i < 4; $i++)
                        <i class="fa fa-star" aria-hidden="true"></i>
                    @endfor
                    <span> - ke atas</span>
                </div>
                <div>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <span> - ke atas</span>
                </div>
                <div>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <span> - ke atas</span>
                </div>
                <div>
                    <i class="fa fa-star" aria-hidden="true"></i>
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
            <div class="keyword-value">Hasil Pencarian
                "<span class="tbold">
                    {{ strlen($_GET['keywords']) <= 40 ? $_GET['keywords'] : substr($_GET['keywords'], -0 , 35) . '.....' }}
                </span>"
            </div>
            <select class="sort-books" name="" id="">
              <option>Paling Laris</option>
              <option>Rating Tertinggi</option>
              <option>Harga Tertinggi</option>
              <option>Harga Terendah</option>
            </select>
        </div>
        <div id="search-filters" class="d-flex">
            <!-- <div class="search-filter">
                <span>Min Rp40.000</span>
                <span class="ml-1">
                    <button class="btn p-0"><i class="fa fa-times text-grey aria-hidden="true"></i></button>
                </span>
            </div> -->
        </div>
        <div id="book-search">
            @include('layouts.books', array('books' => \App\Models\Book::where('name', 'like', '%' . $_GET['keywords'] . '%')->get()))
        </div>

        <div class="d-flex">
            <div class="ml-auto">
                <div class="pagination-custom">
                    <div class="p-active">1</div>
                    <div>2</div>
                    <div>3</div>
                    <div>4</div>
                    <div>5</div>
                    <div><i class="fa fa-caret-right" aria-hidden="true"></i></div>
                </div>
            </div>
        </div>
   </div>
</div>

<div class="click-to-the-top">
    <button class="btn-to-the-top d-flex ml-auto"><i class="to-the-top fa fa-caret-up" aria-hidden="true"></i></button>
</div>

@endsection
