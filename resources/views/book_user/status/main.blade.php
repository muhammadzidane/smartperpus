<div>
    <div class="d-flex justify-content-between">
        <div>
            <h4 class="hd-14">{{ App\Models\User::find($book_user->user_id)->first_name . ' ' . App\Models\User::find($book_user->user_id)->last_name }}</h4>
        </div>
        <div class="text-grey text-right tbold">{{ $book_user->invoice }}</div>
    </div>

    @if ($book_user->payment_status == 'order_in_process')
    <div>Status: <span class="tred-bold">Terkonfirmasi</div>
    @endif

    <div class="d-flex justify-content-between">
        <div>Total pembelian</div>
        <div class="text-grey text-right">{{ $book_user->amount }}</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>Tanggal pembelian</div>
        <div class="text-grey text-right">{{ $book_user->created_at->isoFormat('dddd, D MMMM YYYY H:mm') }}</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>Tanggal deadline</div>
        <div class="text-grey text-right">{{ $book_user->payment_deadline->isoFormat('dddd, D MMMM YYYY H:mm') }}</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>Kurir</div>
        <div class="text-grey text-right">{{ $book_user->courier_name }}</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>Layanan kurir</div>
        <div class="text-grey text-right">{{ $book_user->courier_service }}</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>Ongkos kirim</div>
        <div class="text-grey text-right">{{ $book_user->shipping_cost }}</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>Metode pembayaran</div>
        <div class="text-grey text-right">{{ $book_user->payment_method }}</div>
    </div>
    <div class="d-flex justify-content-between">
        <div>Total pembayaran</div>
        <div class="text-grey text-right">{{ rupiah_format($book_user->total_payment) }}</div>
    </div>
</div>
