<div class="col-md-9">
    @forelse ($book_users as $book_user)
    <div class="upload-payment-value white-content-0">
        <div class="p-15 borbot-gray-bold">
            <div class="d-flex justify-content-between">
                <div>
                    <span>Tanggal Pembelian - </span>
                    <span class="text-grey">{{ $book_user->created_at->isoFormat('dddd, D MMMM YYYY H:mm') }}</span>
                </div>
                <div class="text-right">
                    <span class="text-grey">{{ $book_user->invoice }}</span>
                    @isset($waiting_for_payment)
                    <button class="upload-payment-failed btn-none tred-bold text-right" data-id="{{ $book_user->id }}">Batalkan pembelian</button>
                    @endisset
                </div>
            </div>
        </div>
        <div class="p-4 borbot-gray-bold">
            <div class="ml-1">
                <div class="row">
                    <div class="col-md-2 d-none d-md-block pr-0 text-center">
                        <img class="w-100" src="{{ $content_image }}">
                    </div>
                    <div class="col-md-10 flex-column w-100">
                        <div class="pl-3">
                            <div class="d-flex justify-content-between">
                                <div class="text-righteous">
                                    <span>Total Belanja </span>
                                    <span class="tred-bold">({{ $book_user->amount }})</span>
                                </div>
                                @if ($book_user->payment_status == 'waiting_for_confirmation')
                                <div class="text-right">
                                    <span>Bayar sebelum - </span>
                                    <span class="text-grey">{{ $book_user->payment_deadline->isoFormat('dddd, D MMMM YYYY H:mm') }} WIB</span>
                                </div>
                                @endif
                            </div>
                            <div class="mt-4">
                                <div>Status :
                                    <span class="tred-bold">
                                        @switch($book_user->payment_status)
                                        @case('failed')
                                        {{ 'Tidak berhasil' }}
                                        @break

                                        @case('waiting_for_confirmation')
                                        {{ 'Menunggu pembayaran' }}
                                        @break

                                        @case('order_in_process')
                                        {{ 'Sedang diproses' }}
                                        @break

                                        @case('being_shipped')
                                        {{ 'Sedang dikirim' }}
                                        @break

                                        @case('arrived')
                                        {{ 'Berhasil' }}
                                        @break
                                        @endswitch
                                    </span>
                                </div>
                                <div>Metode pembayaran : <span class="text-grey tbold">{{ $book_user->payment_method }}</span></div>
                                <div>Nama kurir : <span class="text-grey tbold">{{ $book_user->courier_name }}</span></div>
                                <div>Layanan kurir : <span class="text-grey tbold">{{ $book_user->courier_service }}</span></div>
                                <div>Ongkos kirim : <span class="text-grey tbold">{{ rupiah_format($book_user->shipping_cost )}}</span></div>
                                <div>Total pembayaran : <span class="text-grey tbold">{{ rupiah_format($book_user->total_payment) }}</span></div>
                            </div>
                            <div class="mt-3">
                                <div>
                                    <button class="see-billing-list mr-3 btn-none p-0 tred-bold" data-toggle="modal" data-target="#bill" data-id="{{ $book_user->id }}">Lihat Detail Transaksi</button>

                                    @isset ($on_delivery)
                                    <button class="tracking-packages pl-0 mt-4 btn-none tred-bold" data-invoice="{{ $book_user->invoice }}">Lacak Paket</button>
                                    @endif
                                </div>

                                @isset($waiting_for_payment)
                                <div class="mt-4 d-md-flex justify-content-between text-righteous">
                                    <div>
                                        <small class="mt-auto">Bukti pengiriman akan diproses maksimal 24jam (Senin - Jumat)</small>
                                    </div>
                                    @if (!$book_user->upload_payment_image)
                                    <button class="upload-payment-button btn btn-sm-0 btn-red hd-14" data-id="{{ $book_user->id }}">Unggah bukti pembayaran</button>

                                    @else
                                    <span class="btn btn-grey btn-sm-0 hd-14">Sudah mengirim bukti</span>
                                    @endif
                                </div>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @empty
    @include('book_user.status.empty-values', array('text' => 'Tidak ada data yang di proses'))
    @endforelse

    @include('layouts.modal-custom',
    array(
    "modal_trigger_id" => "bill",
    "modal_size_class" => "modal-lg",
    "modal_header" => "Tagihan Anda",
    "modal_content" => "bill"
    )
    )
</div>
