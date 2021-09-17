@extends('layouts.app')
@section('content')

@include('content-header',
array(
'icon_html' => '<i class="user-icon fas fa-users mr-2"></i>',
'title' => 'Daftar Karyawan'
))

@if (session('pesan'))
<div class="alert alert-primary my-4" role="alert">
    <strong>{{ session('pesan') }}</strong>
</div>
@endif

<div id="pesan" class="alert alert-warning my-4 d-none" role="alert">
    <strong>{{ session('pesan') }}</strong>
</div>

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')
    <div class="col-md-9">
        <table class="table white-content-0">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Edit</th>
                    <th>Blokir</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>
                        @if ($user->deleted_at !== null)
                        <span class="text-grey tbold">{{ $user->first_name . ' ' . $user->last_name }}</span>

                        @else
                        <span>{{ $user->first_name . ' ' . $user->last_name }}</span>
                        @endif

                    </td>
                    <td>
                        @if ($user->deleted_at !== null)
                        <span class="text-grey tbold">{{ $user->email }}</span>

                        @else
                        <span>{{ $user->email }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->deleted_at !== null)
                        <span class="text-grey tbold">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>

                        @else
                        <span>{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->deleted_at == null)
                        <a href="/users/{{ $user->id }}/edit" type="button" class="btn btn-success">Edit</a>

                        @else
                        <a type="button" class="btn btn-success">Edit</a>
                        @endif
                    </td>
                    <td>
                        <form data-id="{{ $user->id }}" action="{{ route('users.block', array('user' => $user->id)) }}" method="post">
                            @if ($user->deleted_at == null)
                            <button type="button" class="user-block btn btn-warning">Blokir</button>

                            @else
                            <button type="button" class="user-block btn btn-warning">Lepas Blokir</button>
                            @method('DELETE')
                            @endif
                            @csrf
                        </form>
                    </td>
                    <td>
                        <form data-id="{{ $user->id }}" action="{{ route('users.destroy', array('user' => $user->id)) }}" method="post">
                            <button type="submit" class="user-delete btn btn-danger">Hapus</button>
                            @method('DELETE')
                            @csrf
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
