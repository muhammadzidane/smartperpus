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
                            <a href="{{ route('users.show', array('user' => auth()->user()->id)) }}" class="text-grey"><i class="fas fa-pencil-alt"></i> Akun Saya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-2">
            <div>
                <div class="px-2 pt-2 pb-0 tbold">Pembelian</div>
                <div>
                    <div class="py-2 {{ preg_match('/status\/all|unpaid|on-process|on-delivery|completed|failed/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('status.all') }}">Daftar Transaksi</a>
                    </div>

                    @if (auth()->user()->role != 'guest')
                    <div class="py-2 {{ preg_match('/status\/uploaded-payment$/', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('status.uploaded.payment') }}">Bukti Pembayaran</a>
                    </div>
                    @endif
                </div>
            </div>

            @if (auth()->user()->role == 'guest')
            <div class="py-2 {{ preg_match('/wishlists$/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('wishlists.index') }}">Daftar Wishlist</a>
            </div>

            <div class="py-2">
                <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('carts.index') }}">Keranjang Saya</a>
            </div>

            @else
            <div>
                <div class="px-2 pt-2 pb-0 tbold">Penghasilan</div>
                <div>
                    <div class="py-2 {{ preg_match('/income$/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('income') }}">Daftar Penghasilan</a>
                    </div>
                    <div class="py-2 {{ preg_match('/income\/detail\/today$/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('income.detail.today') }}">Penghasilan Hari ini</a>
                    </div>
                    <div class="py-2 {{ preg_match('/income\/detail\/this-month$/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('income') }}">Penghasilan Bulan Ini</a>
                    </div>
                    <div class="py-2 {{ preg_match('/income\/detail\/all$/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('income') }}">Penghasilan Tahun Ini</a>
                    </div>
                </div>
            </div>

            <div>
                <div class="px-2 pt-2 pb-0 tbold">Karyawan</div>
                <div>
                    <div class="py-2 {{ preg_match('/users\/create/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('users.create') }}">Tambah Karyaran</a>
                    </div>
                    <div class="py-2 {{ preg_match('/users$/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                        <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('users.index') }}">Daftar Karyawan</a>
                    </div>
                </div>
            </div>
            <div>
                <div class="px-2 pt-2 pb-0 tbold">Buku</div>
                <div class="py-2 {{ preg_match('/books\/create/i', request()->path()) ? 'status-sidebar-actice' : '' }}">
                    <a class="px-3 d-block my-auto text-decoration-none mt-2 text-grey" href="{{ route('books.create') }}">Tambah Buku</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
