@extends('layouts/app')
@section('content')

<div class="wkwk1 py-4">
    <div class="d-flex flex-column">
        <h6 class="tbold mt-1">Filter <i class="fas fa-filter"></i></h6>
        <div class="filter-search white-content p-0">
            <div class="p-3">
                <h6 class="tbold borbot-gray-0 pb-2">Berdasarkan Kategori</h6>
                <div class="p-2">
                    @foreach (\App\Models\Category::orderBy('name')->get() as $category)
                    <div>{{ $category->name }}</div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="white-content">
            <form class="p-2" action="">
                <h6 class="tbold borbot-gray-0 pb-2">Minimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0" name="" id="" aria-describedby="helpId" placeholder="">
                    </div>
                </div>

                <h6 class="tbold borbot-gray-0 pb-2 mt-4">Maksimum Harga</h6>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="tbold mt-2 mr-2" for="">Rp</label>
                        <input type="number" class="form-control m-0" name="" id="" aria-describedby="helpId" placeholder="">
                    </div>
                </div>
                <button class="d-flex btn btn-primary mt-2 ml-auto">Terapkan</button>
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
        <div class="d-flex justify-content-between">
            <div class="search-value d-flex pl-3">
                <div class="mr-2 tred-bold active-search">Buku (60)</div>
                <div class="tred-bold">Penulis (0)</div>
            </div>
            <div>Hasil Pencarian "<span class="tbold">Jujutsu Kaisen</span>"</div>
            <select class="sort-books" name="" id="">
              <option>Paling Laris</option>
              <option>Rating Tertinggi</option>
              <option>Harga Tertinggi</option>
              <option>Harga Terendah</option>
            </select>
        </div>
        <div>
            @include('layouts.books', array('books' => \App\Models\Book::get()))
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
