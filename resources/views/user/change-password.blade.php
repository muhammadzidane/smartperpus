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

<div class="py-4">
    <div id="book-create" class="form-register w-75 mx-auto">
    <form id="user-change-password-form" data-id="{{ $user->id }}"
      action="{{ route('users.update.change.password', array('user' => $user->id)) }}" method="POST">
      <div class="text-right p-0"><a href="{{ url()->previous() }}"><i class="fas fa-long-arrow-alt-left text-body"></i></a></div>
        <div class="mb-4">
            <h5 class="tred-bold">Ubah Password</h5>
        </div>

        <div class="d-flex flex-wrap">
            <div class="form-group w-100">
                <label for="password_lama">Password Lama</label>
                <input type="password" name="password_lama" id="password_lama"
                  class="form-control-custom" autocomplete="off">

                @error('password_lama')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                @if(session('pesan_password'))
                    <span class="tred small" role="alert">
                        <strong>{{ session('pesan_password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group w-100">
                <label for="password_baru">Password Baru</label>
                <input type="password" name="password_baru" id="user-password_baru"
                  class="form-control-custom" autocomplete="off">

                @error('password_baru')
                <span class="tred small" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group w-100">
                <label for="ulangi_password_baru">Ulangi Password Baru</label>
                <input type="password" name="ulangi_password_baru" id="ulangi_password_baru"
                  class="form-control-custom" autocomplete="off">

                @error('ulangi_password_baru')
                <span class="tred small" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="form-group mt-4">
            <button class="button-submit active-login" type="submit">Ubah Password</button>
        </div>

        @csrf
    </form>
</div>
<script src="{{ asset('js/helper-functions.js') }}"></script>

@endsection
