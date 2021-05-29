@extends('layouts/app')
@section('content')

@if (session('pesan'))
    <div class="alert alert-primary" role="alert">
        <strong>{{ session('pesan') }}</strong>
    </div>
@endif

<div id="pesan" class="alert alert-warning d-none" role="alert">
    <strong></strong>
</div>

<div class="register-user py-4">
    <div id="book-create" class="form-register w-75 mx-auto">
    <form id="form-register" multiple="multiple" enctype="multipart/form-data"
      action="{{ route('users.update', array('user' => $user->id)) }}" method="POST">
      <div class="text-right p-0"><a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left text-body"></i></a></div>
        <div class="mb-4">
            <h5 class="tred-bold">Edit User</h5>
        </div>

        <div id="error-register"></div>

        <div class="d-flex flex-wrap">
            <div class="form-group w-100">
                <label for="nama_awal">Nama Awal</label>
                <input type="text" name="nama_awal" id="nama_awal"
                  class="form-control-custom user-edit-inp"
                  value="{{ $user->first_name }}" required>

                @error('nama_awal')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group w-100">
                <label for="nama_akhir">Nama Akhir</label>
                <input type="text" name="nama_akhir" id="nama_akhir"
                  class="form-control-custom user-edit-inp"
                  value="{{ $user->last_name }}" required>

                @error('nama_akhir')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group w-100">
                <label for="email">Email</label>
                <input type="text" name="email" id="user-email"
                  class="form-control-custom user-edit-inp"
                  value="{{ $user->email }}" required>

                @error('email')
                <span class="tred small" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group w-100">
                <label for="role">Role</label>
                <select class="form-control-custom" name="role" id="role">
                    <option value="guest"
                        @if ($user->role == 'guest')
                            selected
                        @endif
                    >Guest</option>
                    <option value="admin"
                        @if ($user->role == 'admin')
                            selected
                        @endif
                    >Admin</option>
                    <option value="super_admin"
                        @if ($user->role == 'super_admin')
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

        </div>

        <div class="form-group mt-4">
            <button data-id="{{ $user->id }}" id="user-edit-submit" class="button-submit" type="submit">Edit</button>
        </div>

        @method('PATCH')
        @csrf
    </form>
</div>
<script src="{{ asset('js/helper-functions.js') }}"></script>

@endsection
