<div class="book-deals white-content-0 mt-5">
    <div class="position-relative">
        <h3 class="book-deals-title mr-3">{{ $title }}</h3>
        <a class="show-all" href="{{ isset($search_url) ? $search_url : '#' }}">Lihat Semua</a>
    </div>

    <div class="mt-4">
        @include('layouts.books', array('class_books' => 'justify-content-center'))
    </div>
</div>
