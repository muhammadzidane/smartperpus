<li class="nav-item dropdown self-middle">
    <a id="loginDrowndown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        <div class="user">
            <div class="user-circle">
                @isset (Illuminate\Support\Facades\Auth::user()->profile_image)
                <img id="user-circle-fit" class="w-100" src="{{ asset('storage/users/profiles/' . auth()->user()->profile_image) }}">

                @else
                <div class="h-100 d-flex justify-content-center align-items-center">
                    <i class="fas fa-user"></i>
                </div>
                @endisset
            </div>
            <div class="tred-bold self-middle ml-2">
                <div class="navbar-user-first-name">{{ Str::limit(Auth::user()->first_name, 15, '...') }}</div>
            </div>
        </div>
    </a>

    <div class="dropdown-user dropdown-menu dropdown-menu-right" aria-labelledby="loginDrowndown">
        <div>
            <a class="dropdown-item" href="{{ route('users.show', array('user' => Auth::user()->id)) }}">Akun Saya</a>
        </div>

        @cannot('viewAny', App\Models\User::class)
        <div>
            <a class="dropdown-item" href="{{ route('status.all') }}">Pembelian</a>
        </div>
        @endcannot

        @cannot('viewAny', App\Models\User::class)
        <div>
            <a class="dropdown-item" href="{{ route('wishlists.index') }}">Daftar Wishlist</a>
        </div>
        <div>
            <a class=" dropdown-item" href="{{ route('carts.index') }}">Keranjang Saya</a>
        </div>
        @endcannot

        @can('viewAny', App\Models\User::class)
        <div>
            <a class="dropdown-item" href="{{ route('status.all') }}">Pembelian</a>
        </div>
        <div>
            <a class="dropdown-item" href="{{ route('income') }}">Penghasilan</a>
        </div>
        <div>
            <a class="dropdown-item" href="{{ route('books.create') }}">Tambahkan Buku</a>
        </div>
        <div>
            <a class="dropdown-item" href="{{ route('users.create') }}">Tambahkan Akun</a>
        </div>
        <div>
            <a class="dropdown-item" href="{{ route('users.index') }}">Daftar Karyawan</a>
        </div>
        @endcan

        <div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn dropdown-item text-right text-righteous">
                    Logout
                </button>
            </form>
        </div>
    </div>
</li>
