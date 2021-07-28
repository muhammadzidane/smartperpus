@extends('layouts.app')
@section('content')

<div class="overflow-auto borbot-gray-bold pb-2">
    <div class="w-maxc text-grey">
        <div class="d-flex">
            <i class="user-icon fas fa-user-circle text-grey"></i>
            <h5 class="m-auto"><a class="text-decoration-none text-grey" href="#">Akun Saya</a></h5>
        </div>
    </div>
</div>

<!-- Error Laravel -->
@if (session('pesan'))
<div class="alert alert-primary mt-4" role="alert">
    <strong>{{ session('pesan') }}</strong>
</div>
@endif

<!-- Error JS -->
<div id="pesan" class="d-none alert alert-primary mt-4" role="alert">
    <strong></strong>
</div>

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    <div class="col-md-3 mb-5">
        <div class="white-content m-0 borbot-gray-bold {{ $margin_zero ?? '' }}">
            <div class="borbot-gray-0 pb-3">
                <div class="mt-1">
                    <h4 class="hd-16">Status</h4>

                    @can('viewAny', 'App\\Models\User')
                    <div class="text-grey">
                        <div class="position-relative {{ $waiting_for_payment ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('uploaded.payments') }}">
                                Unggahan bukti pembayaran

                                @if (App\Models\BookUser::get()
                                ->where('payment_status', 'waiting_for_confirmation')
                                ->where('upload_payment_image', '!=' , null)->count() != 0
                                )
                                <div class="status-circle">
                                    {{ App\Models\BookUser::get()
                                    ->where('payment_status', 'waiting_for_confirmation')
                                    ->where('upload_payment_image', '!=' , null)->count()
                                }}
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="position-relative {{ $on_process ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('confirmed.orders') }}">
                                Sedang diproses

                                @if (App\Models\BookUser::get()
                                ->where('upload_payment_image', '!=' , null)
                                ->where('payment_status', 'order_in_process')
                                ->count() != 0
                                )
                                <div class="status-circle">
                                    {{
                                    App\Models\BookUser::get()->where('upload_payment_image', '!=' , null)
                                    ->where('upload_payment_image', '!=' , null)
                                    ->where('payment_status', 'order_in_process')
                                    ->count()
                                }}
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="position-relative {{ $on_delivery ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">
                                Sedang dikirim
                                @if (App\Models\BookUser::get()
                                ->where('upload_payment_image', '!=' , null)
                                ->where('payment_status', 'being_shipped')
                                ->count() != 0
                                )
                                <div class="status-circle">
                                    {{
                                    App\Models\BookUser::get()->where('upload_payment_image', '!=' , null)
                                    ->where('upload_payment_image', '!=' , null)
                                    ->where('payment_status', 'being_shipped')
                                    ->count()
                                }}
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="position-relative {{ $arrived ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">
                                Telah sampai

                                @if (App\Models\BookUser::get()
                                ->where('upload_payment_image', '!=' , null)
                                ->where('payment_status', 'arrived')
                                ->count() != 0
                                )
                                <div class="status-circle">
                                    {{ App\Models\BookUser::get()->where('upload_payment_image', '!=' , null)
                                    ->where('upload_payment_image', '!=' , null)
                                    ->where('payment_status', 'arrived')
                                    ->count()
                                }}
                                </div>
                                @endif
                            </a>
                        </div>
                    </div>

                    @else
                    <div class="text-grey">
                        <div class="{{ $waiting_for_payment ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('waiting.for.payment') }}">Menunggu pembayaran</a>
                            @if (App\Models\BookUser::get()
                            ->where('payment_status', 'waiting_for_confirmation')
                            ->where('upload_payment_image', '!=' , null)->count() != 0
                            )
                            <span class="status-circle">
                                {{ App\Models\BookUser::get()
                                ->where('payment_status', 'waiting_for_confirmation')
                                ->where('upload_payment_image', '!=' , null)->count()
                            }}
                            </span>
                            @endif
                        </div>
                        <div class="{{ $on_process ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang diproses</a>
                        </div>
                        <div class="{{ $on_delivery ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang dikirim</a>
                        </div>
                        <div class="{{ $arrived ?? '' }}">
                            <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Telah sampai</a>
                        </div>
                    </div>
                    @endcan

                </div>
            </div>
            <div class="mt-3">
                <div class="mt-1">
                    <h4 class="hd-16">Kontak Masuk</h4>
                    <div class="text-grey">
                        <div>
                            <a class="text-decoration-none text-grey" href="#">Ulasan</a>
                        </div>
                        <div class="{{ $product_discutions ?? '' }}">
                            <a class="text-decoration-none text-grey" href="#" class=" $product_discutions ?? ''  }}">Chat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="white-content-0 borbot-gray-bold">
            <div class="container">
                <div class="borbot-gray-0">
                    <div class="d-md-flex py-4">
                        <div class="col-md-4 text-center mb-5">
                            @if ($user->profile_image)
                            <img id="user-show-profile" class="profile-img" src="{{ asset('storage/users/profiles/' . $user->profile_image) }}" alt="">

                            @else
                            <img id="user-show-profile" class="profile-img" src="{{ asset('img/avatar-icon.png') }}" alt="">
                            @endif

                            <div class="mt-5">
                                <button id="user-add-photo" class="btn btn-outline-yellow w-100" data-id="{{ $user->id }}">
                                    {{ $user->profile_image ? 'Edit Foto' : 'Tambah Foto' }}
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <h5>Biodata diri</h5>
                                <a href="#" class="tred-bold">Ubah</a>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <div>Nama</div>
                                <div class="text-right">{{ $user->first_name . ' '. $user->last_name }}</div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <div>Tanggal Lahir</div>
                                <div class="text-right">{{ $user->date_of_birth }}</div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <div>Email</div>
                                <div class="text-right">Lara Greyrat</div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <div>Nomer Handphone</div>
                                <div class="text-right">Lara Greyrat</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-4">Besar file: maksimum 2.000 kilobytes (2 Megabytes). Ekstensi file yang diperbolehkan: .JPG .JPEG .PNG</div>
            </div>
        </div>
    </div>
</div>
</div>


</div>


@endsection
