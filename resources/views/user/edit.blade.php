@extends('layouts/app')
@section('content')

@if (session('pesan'))
<div class="alert alert-primary" role="alert">
    <strong>{{ session('pesan') }}</strong>
</div>
@endif

<div id="pesan" class="alert alert-warning d-none tred-bold" role="alert">
    <strong></strong>
</div>

<div class="register-user py-4">
    <div id="book-create" class="form-register w-75 mx-auto">
        <form id="user-edit-form" data-id="{{ $user->id }}" action="{{ route('users.update', array('user' => $user->id)) }}" method="POST">
            <div class="text-right p-0"><a href="/users"><i class="fas fa-long-arrow-alt-left text-body"></i></a></div>
            <div class="mb-4">
                <h5 class="tred-bold">Edit User</h5>
            </div>

            <div class="d-flex flex-wrap">
                <div class="form-group w-100">
                    <label for="nama_awal">Nama Awal</label>
                    <input type="text" name="nama_awal" id="nama_awal" class="form-control-custom user-edit-inp" value="{{ $user->first_name }}">

                    @error('nama_awal')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group w-100">
                    <label for="nama_akhir">Nama Akhir</label>
                    <input type="text" name="nama_akhir" id="nama_akhir" class="form-control-custom user-edit-inp" value="{{ $user->last_name }}">

                    @error('nama_akhir')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group w-100">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="user-email" class="form-control-custom user-edit-inp" value="{{ $user->email }}">

                    @error('email')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group w-100">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control-custom" value="{{ $user->getRawOriginal('date_of_birth') }}">

                    @error('tanggal_lahir')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group w-100">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control-custom">
                        <option value="L" {{ $user->gender === 'L' || old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki - laki</option>
                        <option value="P" {{ $user->gender === 'P' || old('jenis_kelamin') === 'P'? 'selected' : '' }}>Perempuan</option>
                    </select>

                    @error('jenis_kelamin')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group w-100">
                    <label for="nomer_handphone">Nomer Handphone</label>
                    <input type="number" name="nomer_handphone" id="nomer_handphone" class="form-control-custom user-edit-inp" value="{{ $user->phone_number ?? old('nomer_handphone') }}">

                    @error('nomer_handphone')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                @can('viewAny', User::class)
                <div class="form-group w-100">
                    <label for="role">Role</label>
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
                    @error('role')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                @endcan

            </div>

            <div class="form-group mt-4">
                <button id="user-edit-submit" class="button-submit active-login" type="submit">Edit</button>
            </div>

            @method('PATCH')
            @csrf
        </form>
    </div>
    @endsection
