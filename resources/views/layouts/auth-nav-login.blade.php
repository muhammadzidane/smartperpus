<li class="nav-item dropdown self-middle">
    <a id="navbarDropdown" class="nav-link" href="#" role="button"
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

     <div class="dropdown-user dropdown-menu" aria-labelledby="navbarDropdown">
        <div class="mb-3">
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
