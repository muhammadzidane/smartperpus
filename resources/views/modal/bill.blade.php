@foreach ($book_users as $book_user)
<div class="row mt-4 borbot-gray-0 pb-2 px-3">
    <div class="col-sm-3 mb-4">
        <img class="w-100" src="{{ asset('storage\books\\' . $book_user->books[0]->image) }}">
    </div>
    <div class="col-sm-9 d-flex flex-column">
        <div>
            <div>
                <div class="d-flex justify-content-between">
                    <h4 class="hd-14">{{ $book_user->books[0]->name }}</h4>
                </div>
                <h4 class="hd-14 tred">
                    @if ($book_user->book_version == "hard_cover" )
                    <span>Buku Cetak</span>

                    @else
                    <span>E-book</span>
                    @endif
                </h4>
            </div>
            <div class="text-grey">
                <div class="tbold mb-3">{{ $book_user->invoice }}</div>
                <div class="d-flex justify-content-between mb-1">
                    <div>Jumlah barang</div>
                    <div>{{ $book_user->amount }}</div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <div>Harga barang</div>
                    <div>{{ rupiah_format($book_user->books[0]->price - $book_user->books[0]->discount) }}</div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <div>Berat barang</div>
                    <div>{{ $book_user->books[0]->weight }}gram</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
