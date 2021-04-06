@extends('layouts.app')

@section('content')

<div class="register-user">
    <div class="form-register">
        <form action="{{ route('register') }}" method="POST">
            <!-- <div> -->
                <h5 class="tred-bold mb-3">Buat Akun Anda Sekarang</h5>
            <!-- </div> -->
            <div class="pb-2">Sudah memiliki akun? <button data-toggle="modal" data-target="#modelId" id="login" class="btn p-0 tred-bold">Masuk</button></div>
            <div class="form-group">
                <label for="first_name">Nama Awal</label>
                <input type="text" name="first_name" id="first_name" class="form-control-login  ">
                @error('first_name')
                    <span class="tred small small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="last_name">Nama Akhir</label>
                <input type="text" name="last_name" id="last_name" class="form-control-login">
                @error('last_name')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control-login">
                @error('email')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control-login">
                @error('password')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="re_password">Ulangi Password</label>
                <input type="text" name="re_password" id="re_password" class="form-control-login">
                @error('re_password')
                    <span class="tred small" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <button id="button-login" type="submit">Daftar</button>
            </div>
            <div>
                Dengan mendaftarkan akun, anda menyetujui <span class="tred">Syarat & Ketentuan</span> dan
                <span class="tred">Kebijakan Privasi</span> Dari Smartperpus.
            </div>
            @csrf
        </form>
    </div>
    <div class="register-user-pict">
        <img src="{{ asset('img/form-register.jpg') }}">
    </div>
</div>
@endsection
