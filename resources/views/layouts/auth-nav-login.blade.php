<li class="nav-item dropdown self-middle">
    <a id="navbarDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
                <a class="dropdown-item" href="{{ route('books.create') }}">Tambahkan Buku</a>
            </div>

            @can('viewAny', \App\Models\User::class)
            <div>
                <a class="dropdown-item" href="{{ route('users.create') }}">Tambahkan Akun</a>
            </div>
            <div>
                <a class="dropdown-item" href="{{ route('users.index') }}">Daftar Karyawan</a>
            </div>
            @endcan
            @endcan

            <div>
                <a class="dropdown-item" href="{{ route('users.show', array('user' => Auth::user()->id)) }}">Akun Saya</a>
            </div>
            <div>
                <a class="dropdown-item" href="#"">Daftar Wishlist</a>
            </div>
            <div>
                <div class=" position-relative">
                    <a class="dropdown-item" href="{{ route('waiting.for.payment') }}">Menunggu Pembayaran</a>

                    @if (\Illuminate\Support\Facades\DB::table('book_user')->where('user_id', Auth::id())->get()->count() != 0)
                    <div class="waiting-for-payment">
                        {{ Illuminate\Support\Facades\DB::table('book_user')->where('user_id', Auth::id())->get()->count() }}
                    </div>
                    @endif
            </div>
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
