@extends('layouts.app')
@section('content')

@include('content-header',
array(
'icon_html' => '<i class="user-icon fas fa-users mr-2"></i>',
'title' => 'Daftar Karyawan'
))

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')
    <div class="col-md-9">
        <table class="table white-content-0">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Blokir</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>
                        <span class="{{ $user->deleted_at !== null ? 'text-grey tbold' : '' }}">
                            {{ $user->first_name . ' ' . $user->last_name  }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ $user->deleted_at !== null ? 'text-grey tbold' : '' }}">{{ $user->email }}</span>
                    </td>
                    <td>
                        <span class="{{ $user->deleted_at !== null ? 'text-grey tbold' : '' }}">{{ ucwords(str_replace('_', ' ', $user->role)) }}</span>
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
        <div class="d-flex justify-content-end mt-4">{{ $users->links() }}</div>
    </div>
</div>

@endsection
