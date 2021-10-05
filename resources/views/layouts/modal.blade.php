<div class="modal fade" id="{{ $modal_id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog {{ $modal_size_class }} modal-dialog-centered">
        <div class="modal-content">
            <div class="p-3 position-relative">
                <h5 class="modal-title tred login-header">{{ $modal_header }}</h5>
                <button id="modal-exit-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
              {!! $modal_content !!}
            </div>
        </div>
    </div>
</div>
