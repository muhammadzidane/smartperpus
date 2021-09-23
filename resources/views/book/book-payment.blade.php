@extends('layouts.app')
@section('content')

<div class="text-center">
    <h4>Lakukan pembayaran dalam</h4>
    <h1 id="payment-limit-time" class="text-righteous my-4">
        <span id="deadline-hours">{{ $deadline_time['hours'] }}</span> :
        <span id="deadline-minutes">{{ $deadline_time['minutes'] }}</span> :
        <span id="deadline-seconds">{{ $deadline_time['seconds'] }}</span>
    </h1>
    <div>
        <span>Batas waktu pembayaran :</span>
        <span class="tbold">{{ $first_book_user->payment_deadline->isoFormat('dddd, D MMMM YYYY HH:mm') }}</span>
    </div>
</div>

<div class="row flex-column col-sm-8 mx-auto">
    <div class="white-content p-0 pb-4 borbot-gray-bold mt-4">
        <div class="p-3 borbot-gray">
            @switch($first_book_user->payment_method)
                @case('Transfer Bank BRI')
                    <h5>Transfer Bank BRI</h5>
                @break
                @case('Transfer Bank BNI')
                    <h5>Transfer Bank BNI</h5>
                @break
                @case('Transfer Bank BCA')
                    <h5>Transfer Bank BCA</h5>
                @break
            @endswitch
        </div>
        <div class="mt-4">
            <div class="container text-center borbot-gray-0 pb-4">
                <h5>Transfer pembayaran pada nomer rekening berikut :</h5>
                <div class="mt-4">
                    <div>
                        <div class="d-flex">
                            <div>
                                @switch($first_book_user->payment_method)
                                    @case('Transfer Bank BRI')
                                        <img src="{{ asset('img/transfer/bri.png') }}" class="w-20">
                                    @break
                                    @case('Transfer Bank BNI')
                                        <img src="{{ asset('img/transfer/bni.png') }}" class="w-20">
                                    @break
                                    @case('Transfer Bank BCA')
                                        <img src="{{ asset('img/transfer/bca.png') }}" class="w-20">
                                    @break
                                @endswitch

                                <h4 class="d-inline align-middle tred ml-3 f-28">1903847182123</h4>
                            </div>
                        </div>
                        <h4 class="mt-4 hd-14">Atas nama <span class="text-grey">Muhammad Zidane Alsaadawi</span></h4>
                        <div class="text-grey"><i class="fa fa-info-circle" aria-hidden="true"></i> Kami akan cek dalam 24 jam setelah bukti transfer di upload</div>
                    </div>
                </div>
            </div>
            <div class="d-flex mt-4 px-4">
                <div class="w-100">
                    <div id="accordion">
                        <div>
                            <div id="headingOne" class="d-flex justify-content-between border py-2 px-2">
                                <button class="btn-none collapsed p-0 w-100 text-left" data-toggle="collapse" data-target="#detailCollapse" aria-expanded="false" aria-controls="detailCollapse">
                                    Lihat Detail
                                </button>
                                <i class="my-auto fa fa-caret-down" aria-hidden="true"></i>
                            </div>
                            <div id="detailCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="mt-3">
                                    <div class="mb-3">
                                        <div class="tbold">Alamat Pengiriman</div>
                                        <div>
                                            <div>{{ $customer->name }}</div>
                                            <div>{{ $customer->phone_number }}</div>
                                            <div>{{ $customer->address }}, Kec.{{ $customer->district->name }}, {{ $customer->city->name }} {{ $customer->city->type }} . {{ $customer->province->name }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="tbold">Kurir</div>
                                        <div class="text-grey">{{ $courier_name }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="tbold">Layanan Kurir</div>
                                        <div class="text-grey">{{ $first_book_user->courier_service }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="tbold">Ongkos Kirim</div>
                                        <div class="text-grey">+{{ rupiah_format($first_book_user->shipping_cost) }}</div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="tbold">Kode Unik</div>
                                        <div class="text-grey">+{{ rupiah_format($first_book_user->unique_code) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total Pembayaran</h5>
                                <div class="tred-bold hd-18">
                                    <span>{{ $total_payment }}</span>
                                    <span class="bg-warning">{{ $total_payment_sub_last }}</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="my-auto mr-2">Contoh Foto Transfer</div>
                                <div>
                                    <img class="zoom-modal-image transfer-sample" src="{{ asset('img/sample-transfer.jpg') }}" alt="Contoh Foto Transfer">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">Pastikan nominal transfer <span class="tbold">TEPAT BERJUMLAH ANGKA DI ATAS (Hingga 3 angka terakhir)</span></div>
                    </div>
                    <div class="mt-4 text-grey"><i class="fa fa-info-circle" aria-hidden="true"></i> Unggah bukti pembayaran agar proses cepat dilakukan</div>
                    <div class="mt-3 row">
                        <div class="col-md-6">
                            @if ($first_book_user->upload_payment_image == null)
                                @if ($deadline_has_reached == false)
                                    <button class="upload-payment-button btn btn-outline-danger w-100" data-toggle="modal" data-target="#modal-upload-payment">Upload Bukti Pembayaran</button>

                                    <!-- Modal Upload -->
                                    <div class="modal fade" id="modal-upload-payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="p-3 position-relative">
                                                    <h5 class="modal-title tred login-header">Upload Bukti Pembayaran</h5>
                                                    <button id="modal-exit-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="upload-payment">
                                                        <form id="upload-payment-form" action="{{ route('book-purchases.upload', array('invoice' => $first_book_user->invoice)) }}" enctype="multipart/form-data" method="POST">
                                                            <div class="upload-payment-select-image">
                                                                <button type="button" id="upload-payment-cancel" class="btn-none d-none"><i class="fa fa-times" aria-hidden="true"></i></button>
                                                                <label>
                                                                    <input id="upload-payment-file" type="file" class="d-none" name="upload_payment" accept=".jpg, .jpeg, .png">
                                                                    <i id="upload-payment-plus-logo" class="fa fa-plus" aria-hidden="true"></i>
                                                                </label>
                                                                <div>
                                                                    <img id="upload-payment-image" class="w-100 d-none" src="" alt="gambar">
                                                                </div>
                                                                <button id="upload-payment-submit-button" class="btn btn-red d-none mt-3" type="submit">Kirim</button>
                                                            </div>
                                                            @method('PATCH')
                                                            @csrf
                                                        </form>
                                                        <div class="upload-payment-information">
                                                            <div class="text-grey bold">
                                                                <div>File harus berupa jpg, jpeg, png</div>
                                                                <div>Maksimal 2mb</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    <button class="btn btn-outline-danger w-100" disabled>Masa pembayaran sudah habis</button>
                                @endif

                            @else
                                <button class="upload-payment-button btn btn-outline-danger w-100" disabled>Bukti Sedang Diproses</button>
                            @endif
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <a href="{{ route('status.unpaid') }}#{{ $first_book_user->invoice }}" class="btn btn-outline-danger w-100">Upload Nanti</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-content pt-0 mt-4 borbot-gray-bold">
        <div class="py-3 borbot-gray">
            <h5>Petunjuk Pembayaran</h5>
        </div>

        @foreach ($payment_instructions as $key => $service)
            <div class="py-1 c-p">
                <div class="borbot-gray mt-2 pb-1">
                    <div class="collapse-intro container">
                        <button class="btn-intro text-left btn-none p-0 w-100" data-toggle="collapse" href="#{{ $key }}" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <h4 class="hd-18">
                                {{ $service['title'] }} <i class="fa fa-caret-down ml-2"></i>
                            </h4>
                        </button>
                    </div>
                    <div id="{{ $key }}" class="payment-intro collapse mt-2 container">
                        <div>
                            <ol class="text-grey">
                                @foreach ($service['lists'] as $list)
                                    <li>{{ $list }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            <span>
                <i class="fas fa-info-circle"></i>
            </span>
            <span class="tred-bold">Pembayaran dari Bank lain ke Bank BNI, Dikenakan biaya transaksi antarbank (Rp 5.000 – Rp 8.000)</span>
        </div>
    </div>
</div>

@section('script')
    <script src="{{ asset('js/book_payment/index.js') }}"></script>
@endsection

@endsection
