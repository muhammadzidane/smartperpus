@extends('layouts.app')
@section('content')

<div class="borbot-gray-bold overflow-auto">
    <div class="w-maxc d-flex c-p text-grey">
        <div class="d-flex mr-4">
            <i class="fa fa-heart mr-2 tred f-20" aria-hidden="true"></i>
            <h4 class="hd-14">Daftar Wishlist</h4>
        </div>
        <div class="d-flex mr-4">
            <i class="fas fa-shopping-basket mr-2 text-green f-20"></i>
            <h4 class="hd-14">Keranjang Saya</h4>
        </div>
        <div class="d-flex mr-4 active-authbook">
            <i class="fas fa-user-circle mr-2 f-20 text-grey"></i>
            <h4 class="hd-14">Akun Saya</h4>
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

<div class="row d-md-flex mt-md-4">
    <div class="col-lg-9 d-lg-flex">
        <div class="white-content px-0 pt-0 pb-4 m-0 borbot-gray-bold w-100">
            <div class="container-lg pt-4">
                <div class="change-profile container-lg d-md-flex pb-4 borbot-gray-0">
                    <div>
                        <div class="d-flex flex-column">
                            <div>
                                @if ($user->profile_image)
                                    <img class="profile-img" src="{{ url('storage/user/profile/' . $user->profile_image ) }}">
                                @else
                                    <img class="profile-img" src="{{ asset('img/avatar-icon.png') }}">
                                @endif
                            </div>
                            <div class="mt-5">
                                @if (!$user->profile_image)
                                    <div>
                                        <button type="button" id="add-account-photo" data-toggle="modal" data-target="#modal-change-photo"
                                        class="btn btn-sm btn-outline-yellow w-100">
                                            <i class="fas fa-images"></i>
                                            <span>Tambah Foto</span>
                                        </button>
                                    </div>
                                @else
                                    <div>
                                        <button type="button" id="add-account-photo" data-toggle="modal" data-target="#modal-change-photo"
                                        class="btn btn-sm btn-outline-yellow w-100">
                                            <i class="fas fa-images"></i>
                                            <span>Edit Foto</span>
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <form id="user-destroy-photo-profile-form" data-id="{{ $user->id }}"
                                          action="{{ route('users.destroy.photo.profile', array('user' => $user->id)) }}" method="post">
                                            <input type="hidden" name="photo_profile" id="photo_profile" value="{{ $user->profile_image }}">
                                            <button type="submit" class="btn-none w-100 tred-bold">Hapus Foto</button>
                                            @csrf
                                        </form>
                                    </div>
                                @endif

                                @error('foto_profile')
                                    <div class="mt-2 tred small small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror

                                <!-- Modal Change Photo Profile -->
                                <div class="modal fade" id="modal-change-photo" tabindex="-1" role="dialog"
                                  aria-labelledby="modal-change-photoTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content modal-content-login">
                                            <div class="px-3 mb-4 d-flex justify-content-between">
                                                @if (!$user->profile_image)
                                                    <h5 class="modal-title tred login-header">Tambah Foto Profile</h5>
                                                @else
                                                    <h5 class="modal-title tred login-header">Edit Foto Profile</h5>
                                                @endif
                                                <button id="login-exit" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>

                                            </div>
                                            <div class="modal-body">
                                                <div>
                                                    <div class="w-maxc mx-auto">
                                                    @if ($user->profile_image)
                                                        <img class="profile-img" src="{{ url('storage/user/profile/' . $user->profile_image ) }}">
                                                    @else
                                                        <img class="profile-img" src="{{ asset('img/avatar-icon.png') }}">
                                                    @endif
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <form id="user-change-photo-form" action="{{ route('users.add.photo.profile') }}" method="post" enctype="multipart/form-data">
                                                        <div class="form-group">
                                                            <div class="text-center">
                                                                <input type="file" name="foto_profile" id="foto_profile">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            @if (!$user->profile_image)
                                                                <button class="button-submit" type="submit">Tambah</button>
                                                            @else
                                                                <button class="button-submit" type="submit">Edit</button>
                                                            @endif
                                                        </div>
                                                        @csrf
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @csrf
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column w-100">
                        <div class="ml-md-5 overflow-auto">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="hd-18">Biodata diri</h4>
                                </div>
                                <div><a href="{{ route('users.edit', array('user' => $user->id)) }}" class="btn-none p-0 tred-bold text-decoration-none">Ubah</a></div>

                            </div>
                            <table class="account-table">
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $user->date_of_birth ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>
                                        @if ($user->gender === null)
                                            {{ '-' }}
                                        @elseif ($user->gender === 'L')
                                            {{ 'Laki - laki' }}
                                        @else
                                            {{ 'Perempuan' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Nomer Handphone</th>
                                    <td>{{ $user->phone_number ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="ml-auto mt-auto">
                            <a href="{{ route('users.show.change.password', array('user' => $user->id)) }}" class="btn btn-sm btn-outline-yellow">Ubah Password</a>
                        </div>
                    </div>
                </div>
                <div class="mx-3 mt-3 change-profile-img">
                    <p class="text-grey">Besar file: maksimum 2.000.000 bytes (2 Megabytes). Ekstensi file yang diperbolehkan: .JPG .JPEG .PNG</p>
                </div>
            </div>
        </div>
    </div>
    @include('user.purchases-and-inboxes')
</div>


@endsection
