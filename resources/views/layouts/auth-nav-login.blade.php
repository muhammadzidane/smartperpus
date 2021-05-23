<li class="nav-item dropdown self-middle">
    <a id="navbarDropdown" href="#" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        <div class="user">
            <div class="user-circle self-middle">
                <i class="fas fa-user"></i>
            </div>
            <div class="tred-bold self-middle ml-2">
                <div>{{ Auth::user()->first_name }}</div>
            </div>
        </div>
    </a>

     <div class="dropdown-user dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <div class="mb-3">
            @can('viewAny', \App\Models\Book::class)
                <div>
                    <a class="dropdown-item" href="#">Tambahkan Buku</a>
                </div>
                @can('viewAny', \App\Models\User::class)
                    <div>
                        <a class="dropdown-item" href="{{ route('user.create') }}">Tambahkan Akun</a>
                    </div>
                @endcan
            @endcan
            <div>
                <a class="dropdown-item" href="#"">Akun Saya</a>
            </div>
            <div>
                <a class="dropdown-item" href="#"">Daftar Wishlist</a>
            </div>
            <div>
                <a class="dropdown-item" href="#">Keranjang Saya</a>
            </div>
        </div>
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
