<div class="book-deals">
    <div class="position-relative">
        <h3 class="book-deals-title mr-3">{{ $title }}</h3>
            <a class="show-all" href="#">Lihat Semua</a>
    </div>

    @if(session('pesan'))
        <div class="alert alert-primary" role="alert">
            <strong>{{ session('pesan') }}</strong>
        </div>
    @endif

    @include('layouts.books')
</div>

