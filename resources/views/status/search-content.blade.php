<div class="row">
    <div class="col-12">
        <form action="{{ url()->current() }}" method="GET">
            <div class="status-search">
                <button class="status-search-icon btn-none p-0">
                    <i class="fa fa-search d-none d-md-block" aria-hidden="true"></i>
                </button>
                <input name="filter" class="status-search-input" type="text" placeholder="{{ $placeholder }}" value="{{ request()->filter  }}">
            </div>
        </form>

        @if (request()->filter)
            <div class="text-grey mt-4">
                <span>Hasil pencarian untuk</span>
                <span class="tbold">"{{ request()->filter }}"</span>
                <span>. Menampilkan <span class="tbold">{{ $count ?? 0 }}</span> hasil </span>
            </div>
        @endif
    </div>
</div>
