<div class="col-md-3 mb-4">
    <div class="white-content-0 m-0 borbot-gray-bold">
        <div class="container pt-3">
            <div class="borbot-gray-0 pb-3">
                <div class="row">
                    <div class="col-md-4 col-4">
                        <div class="status-profile">
                            <img class="w-100" src="{{ asset(auth()->user()-> profile_image ? 'storage/users/profiles/' . auth()->user()->profile_image : 'img/avatar-icon.png') }}" alt="Foto Profile">
                        </div>
                    </div>
                    <div class="col-md-8 col-8 pl-0">
                        <div class="pt-1">
                            <div class="tbold">{{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}</div>
                            <a href="{{ route('users.show', array('user' => auth()->user()->id)) }}" class="text-grey"><i class="fas fa-pencil-alt"></i> Ubah Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-2">
            <div class="py-2 {{ preg_match('/users\/[0-9]{1}/i', request()->path()) ? 'active-acc' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none text-grey" href="{{ route('users.show', array('user' => auth()->user()->id)) }}">Akun Saya</a>
            </div>
            <div class="py-2 {{ preg_match('/status\/[\s\S]{1}/i', request()->path()) ? 'active-acc' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('status.all') }}">Pembelian</a>
            </div>

            @if (auth()->user()->role == 'guest')
            <div class="py-2 {{ preg_match('/users[\s\S]{0}/i', request()->path()) ? 'active-acc' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('wishlists.index') }}">Daftar Wishlist</a>
            </div>
            <div class="py-2 {{ preg_match('/status[\s\S]{0}/i', request()->path()) ? 'active-acc' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('carts.index') }}">Keranjang Saya</a>
            </div>

            @else
            <div class="py-2 {{ preg_match('/books\/create/i', request()->path()) ? 'active-acc' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('books.create') }}">Tambah Buku</a>
            </div>
            <div class="py-2 {{ preg_match('/users\/create/i', request()->path()) ? 'active-acc' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('users.create') }}">Tambah Karyaran</a>
            </div>
            <div class="py-2 {{ preg_match('/users$/i', request()->path()) ? 'active-acc' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('users.index') }}">Daftar Karyawan</a>
            </div>
            @endif
        </div>
    </div>
</div>
