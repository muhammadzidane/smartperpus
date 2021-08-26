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

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    <div class="col-12">
        <div class="white-content-0 borbot-gray-bold">
            <div class="container">
                <div class="borbot-gray-0">
                    <div class="d-md-flex py-4">
                        <div class="col-md-4 text-center mb-5">
                            @if ($user->profile_image)
                            <img id="user-show-profile" class="profile-img zoom-modal-image" src="{{ asset('storage/users/profiles/' . $user->profile_image) }}" alt="">

                            @else
                            <img id="user-show-profile" class="profile-img zoom-modal-image" src="{{ asset('img/avatar-icon.png') }}" alt="">
                            @endif

                            <div class="mt-5">
                                <button id="user-add-photo" class="btn btn-outline-yellow w-100" data-id="{{ $user->id }}">{{ $user->profile_image ? 'Edit Foto' : 'Tambah Foto' }}</button>
                            </div>
                            <div class="mt-3">
                                <button id="user-change-email" type="button" class="btn btn-outline-yellow w-100" data-id="{{ $user->id }}">Ubah Email</button>
                            </div>
                            <div class="mt-3">
                                <button id="user-change-password" type="button" class="btn btn-outline-yellow w-100" data-id="{{ $user->id }}">Ubah Password</button>
                            </div>
                            @isset ($user->profile_image)
                            <div class="mt-2">
                                <form id="user-delete-photo-form" action="/users/{{ $user->id }}/destroy-photo" method="post">
                                    <button type="submit" class="btn-none tred-bold">Hapus Foto</button>
                                    @method('PATCH')
                                    @csrf
                                </form>
                            </div>
                            @endisset
                        </div>
                        <div class="col-md-8 mt-3">
                            <div class="borbot-gray-0 pb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5>Biodata diri</h5>
                                    <button id="user-change-biodata" class="btn-none tred-bold" type="button" data-id="{{ $user->id }}">Ubah</button>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Nama</div>
                                    <div class="text-right">
                                        <span id="user-first-name">{{ $user->first_name }}</span>
                                        <span id="user-last-name">{{ $user->last_name }}</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Tanggal Lahir</div>
                                    <div id="user-date-of-birth" class="text-right">{{ $user->date_of_birth ?? '-' }}</div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Jenis Kelamin</div>
                                    <div id="user-gender" class="text-right">{{ ($user->gender == 'L' ? 'Laki-laki' : 'Perempuan') ?? '-' }}</div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Alamat</div>
                                    <div id="user-address" class="text-right">{{ $user->address ?? '-' }}</div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Email</div>
                                    <div id="user-email" class="text-right">{{ $user->email }}</div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Nomer Handphone</div>
                                    <div id="user-phone-number" class="text-right">{{ $user->phone_number ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between">
                                    <h5 id="user-customer-title">Daftar Alamat Pengiriman</h5>
                                    @if (auth()->user()->customers->count() != 0 && auth()->user()->customers->count() < 5) <button id="user-create-customer" class="btn-none tred-bold">Tambah</button>
                                        @endif
                                </div>
                                <div id="user-customer-lists">
                                    @forelse (auth()->user()->customers as $customer)
                                    <div class="user-customer mt-3 d-flex borbot-gray-0 pb-2">
                                        <div class="d-flex">
                                            <div>
                                                <div>
                                                    <span class="customer-name">{{ $customer->name }}</span> -
                                                    <span class="customer-phone-number">{{ $customer->phone_number }}</span>
                                                </div>
                                                <div>
                                                    <span class="customer-address">{{ $customer->address }}</span>.
                                                    <span class="customer-province" data-province="{{ $customer->province->id }}">{{ $customer->province->name }},</span>
                                                    <span class="customer-city" data-city="{{ $customer->city->id }}">{{ $customer->city->type  . ' ' . $customer->city->name }},</span>
                                                    <span class="customer-district" data-district="{{ $customer->district->id }}">{{ $customer->district->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-auto text-right">
                                            <div>
                                                <button class="user-customer-update btn-none tred-bold" type="button" data-id="{{ $customer->id }}">Ubah</button>
                                            </div>
                                            <div>
                                                <button class="user-customer-delete btn-none tred-bold" type="button" data-id="{{ $customer->id }}">Hapus</butt>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div>
                                        <span id="user-customer-message">Anda belum memiliki alamat pengiriman.</span>
                                        <button id="user-create-customer" class="btn-none tred-bold">Tambah</button>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-4 text-grey">Besar file: maksimum 2.000 kilobytes (2 Megabytes). Ekstensi file yang diperbolehkan: .JPG .JPEG .PNG</div>
            </div>
        </div>
    </div>
</div>
</div>


</div>


@endsection
