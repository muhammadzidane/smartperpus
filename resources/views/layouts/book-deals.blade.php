<div class="white-content mt-5">
    <div class="d-flex justify-content-between position-relative borbot-gray p-2">
        <h4 class="mr-3">{{ $title }}</h4>
        <div class="tbold">
            <a href="{{ isset($search_url) ? $search_url : '#' }}">Lihat Semua</a>
            <i class="ml-1 fa fa-caret-right"></i>
        </div>
    </div>

    <div class="mt-4">
        @include('layouts.books', array('class_books' => 'justify-content-center'))
    </div>
</div>
