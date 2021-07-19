<div class="white-content-0 borbot-gray-bold">
    <div class="white-content-header-2">
        <div>
            <h4 class="hd-14 m-0 d-inline"><a href="{{ route('uploaded.payments') }}" class="{{ $uploaded_payment ?? 'text-grey' }} text-decoration-none">Unggahan Bukti Pembayaran</a></h4>
            <a href="{{ route('confirmed.orders') }}" class="ml-3 text-grey tbold text-decoration-none {{ $confirmed ?? '' }}">Order Yang Terkonfirmasi</a>
            <a href="{{  route('on.delivery') }}" class="ml-3 text-grey tbold text-decoration-none {{ $on_delivery ?? '' }}">Sedang di Kirim</a>
            <a href="#" class="ml-3 text-grey tbold text-decoration-none {{ $arrived ?? '' }}">Telah Sampai</a>
            <a href="#" class="ml-3 text-grey tbold text-decoration-none {{ $history ?? '' }}">Riwayat</a>
        </div>
    </div>
</div>
