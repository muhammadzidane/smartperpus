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
            </div>
        </div>
    </div>
</div>
