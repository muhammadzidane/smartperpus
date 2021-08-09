<li class="nav-item dropdown self-middle">
    <a id="navbarDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        <div class="user">
            <div class="user-circle self-middle">
                @isset (Illuminate\Support\Facades\Auth::user()->profile_image)
                <img id="user-circle-fit" src="{{ asset('storage/users/profiles/' . Illuminate\Support\Facades\Auth::user()->profile_image) }}">

                @else
                <i class="fas fa-user"></i>
                @endisset
            </div>
            <div class="tred-bold self-middle ml-2">
                <div class="navbar-user-first-name">{{ Auth::user()->first_name }}</div>
            </div>
        </div>
    </a>

    <div class="dropdown-user dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <div>
            <a class="dropdown-item" href="{{ route('users.show', array('user' => Auth::user()->id)) }}">Akun Saya</a>
        </div>
        @cannot('viewAny', App\Models\User::class)
        <div>
            <a class="dropdown-item" href="{{ route('status.waiting.for.payment') }}">Pembelian</a>
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
            <a class="dropdown-item" href="{{ route('uploaded.payments') }}">Status</a>
        </div>
        <div>
            <a class="dropdown-item" href="{{ route('book.users.status.income') }}">Penghasilan</a>
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
