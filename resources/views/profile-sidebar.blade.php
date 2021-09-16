<div class="col-md-3 mb-4">
    <div class="white-content m-0 borbot-gray-bold">
        <div class="borbot-gray-0 pb-3">
            <div>
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
        <div class="mt-2">
            <div class="d-flex flex-column">
                <a class="text-decoration-none mt-2 text-grey" href="{{ route('users.show', array('user' => auth()->user()->id)) }}">Akun Saya</a>
                <a class="text-decoration-none mt-2 text-grey" href="{{ route('status.all') }}">Pembelian</a>
                <a class="text-decoration-none mt-2 text-grey" href="{{ route('wishlists.index') }}">Daftar Wishlist</a>
                <a class="text-decoration-none mt-2 text-grey" href="{{ route('carts.index') }}">Keranjang Saya</a>
            </div>
        </div>
    </div>
</div>
