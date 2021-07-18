@foreach ($book_users as $book_user)
<div class="row mt-4 borbot-gray-0 pb-2 px-3">
    <div class="col-3">
        <img class="w-100" src="{{ asset('storage\books\\' . App\Models\Book::find($book_user->book_id)->image) }}">
    </div>
    <div class="col-9">
        <div>
            <h4 class="hd-14">{{ App\Models\Book::find($book_user->book_id)->name }}</h4>
            <h4 class="hd-14 tred">
                @if ($book_user->book_version == "hard_cover" )
                <span>Buku Cetak</span>

                @else
                <span>Ebook</span>
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
                <div>{{ rupiah_format(App\Models\Book::find($book_user->book_id)->price) }}</div>
            </div>
        </div>
    </div>
</div>
@endforeach
