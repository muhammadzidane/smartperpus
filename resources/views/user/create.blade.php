@extends('layouts/app')
@section('content')


@include('content-header',
array(
'icon_html' => '<i class="fas fa-user-plus user-icon fas fa-user-circle text-grey"></i>',
'title' => 'Tambah Karyawan'
))

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')
    <div class="col-md-9">
        <div class="register-user">
            <div id="book-create" class="form-register">
                <form id="user-store-form" action="/users" method="POST">
                    <div class="mb-4">
                        <h5 class="tred-bold"> {{ isset($user) ? 'Edit Karyawan' : 'Tambah Karyawan' }}</h5>
                    </div>

                    <div class="d-flex flex-wrap">
                        <div class="form-group w-100">
                            <label for="nama_awal">Nama Awal*</label>
                            <input type="text" name="nama_awal" id="nama_awal" class="form-control-custom user-edit-inp" value="{{ isset($user) ? $user->first_name : old('nama_awal') }}">
                        </div>
                        <div class="form-group w-100">
                            <label for="nama_akhir">Nama Akhir*</label>
                            <input type="text" name="nama_akhir" id="nama_akhir" class="form-control-custom user-edit-inp" value="{{ isset($user) ? $user->last_name : old('nama_akhir') }}">
                        </div>
                        <div class="form-group w-100">
                            <label for="email">Email*</label>
                            <input type="text" name="email" id="user-email" class="form-control-custom user-edit-inp" value="{{ isset($user) ? $user->email : old('email') }}">
                        </div>
                        @empty($user)
                        <div class="form-group w-100">
                            <label for="role">Role*</label>
                            <div>
                                <select class="form-control-custom" name="role" id="role">
                                    <option value="admin">Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>

                            </div>
                        </div>
                        @endempty
                        <div class="form-group w-100">
                            <label for="tanggal_lahir">Tanggal Lahir*</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control-custom" value="{{ isset($user) ? $user->getRawOriginal('date_of_birth') : old('tanggal_lahir') }}">
                        </div>
                        <div class="form-group w-100">
                            <label for="jenis_kelamin">Jenis Kelamin*</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control-custom">
                                <option value="L" {{ (isset($user) ? $user->gender : '') === 'L' || old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki - laki</option>
                                <option value="P" {{ (isset($user) ? $user->gender : '') === 'P' || old('jenis_kelamin') === 'P'? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group w-100">
                            <label for="nomer_handphone">Nomer Handphone*</label>
                            <input type="number" name="nomer_handphone" id="nomer_handphone" class="form-control-custom user-edit-inp" value="{{ $user->phone_number ?? old('nomer_handphone') }}">
                        </div>

                        @can('viewAny', User::class)
                        <div class="form-group w-100">
                            <label for="role">Role*</label>
                            <select class="form-control-custom" name="role" id="role">
                                <option value="guest" @if ($user->role == 'guest')
                                    selected
                                    @endif
                                    >Guest</option>
                                <option value="admin" @if ($user->role == 'admin')
                                    selected
                                    @endif
                                    >Admin</option>
                                <option value="super_admin" @if ($user->role == 'super_admin')
                                    selected
                                    @endif
                                    >Super Admin</option>
                            </select>
                        </div>
                        @endcan
                    </div>

                    <div class="form-group mt-4">
                        <button id="user-edit-submit" class="cursor-disabled btn btn-outline-danger w-100" type="submit" disabled>Tambah</button>
                    </div>
                    @csrf
                </form>
            </div>
            @endsection
        </div>
    </div>
</div>
